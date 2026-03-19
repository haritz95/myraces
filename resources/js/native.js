/**
 * Native bridge — only runs inside a Capacitor shell.
 * Progressively enhances the existing PWA without breaking the web version.
 */
import { Capacitor } from '@capacitor/core';
import { App } from '@capacitor/app';
import { StatusBar, Style } from '@capacitor/status-bar';
import { SplashScreen } from '@capacitor/splash-screen';
import { Haptics, ImpactStyle } from '@capacitor/haptics';
import { PushNotifications } from '@capacitor/push-notifications';

const isNative = Capacitor.isNativePlatform();

if (isNative) {
    initNative();
}

async function initNative() {
    // Status bar — dark style, same background as the app
    await StatusBar.setStyle({ style: Style.Dark });
    await StatusBar.setBackgroundColor({ color: '#0a0a0a' });

    // Hide splash screen once the page is interactive
    document.addEventListener('DOMContentLoaded', () => {
        SplashScreen.hide();
    });

    // Handle Android back button
    App.addListener('backButton', ({ canGoBack }) => {
        if (canGoBack) {
            window.history.back();
        } else {
            App.exitApp();
        }
    });

    // Register push notifications
    await registerPushNotifications();

    // Expose haptic feedback globally so existing Alpine.js code can use it
    window.nativeHaptic = async (style = 'medium') => {
        const map = {
            light: ImpactStyle.Light,
            medium: ImpactStyle.Medium,
            heavy: ImpactStyle.Heavy,
        };
        await Haptics.impact({ style: map[style] ?? ImpactStyle.Medium });
    };
}

async function registerPushNotifications() {
    let permStatus = await PushNotifications.checkPermissions();

    if (permStatus.receive === 'prompt') {
        permStatus = await PushNotifications.requestPermissions();
    }

    if (permStatus.receive !== 'granted') {
        return;
    }

    await PushNotifications.register();

    PushNotifications.addListener('registration', async (token) => {
        // Send token to Laravel backend
        await fetch('/api/push-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            },
            body: JSON.stringify({
                token: token.value,
                platform: Capacitor.getPlatform(),
            }),
        });
    });

    PushNotifications.addListener('pushNotificationReceived', (notification) => {
        console.log('Push received:', notification);
    });

    PushNotifications.addListener('pushNotificationActionPerformed', (action) => {
        const url = action.notification.data?.url;
        if (url) {
            window.location.href = url;
        }
    });
}

export { isNative };
