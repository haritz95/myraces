import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
    appId: 'app.myraces',
    appName: 'MyRaces',
    webDir: 'public',
    server: {
        url: 'https://myraces.app',
        cleartext: false,
    },
    plugins: {
        SplashScreen: {
            launchShowDuration: 1500,
            launchAutoHide: true,
            backgroundColor: '#0a0a0a',
            androidSplashResourceName: 'splash',
            showSpinner: false,
        },
        StatusBar: {
            style: 'dark',
            backgroundColor: '#0a0a0a',
        },
        PushNotifications: {
            presentationOptions: ['badge', 'sound', 'alert'],
        },
    },
    ios: {
        contentInset: 'always',
        backgroundColor: '#0a0a0a',
    },
    android: {
        backgroundColor: '#0a0a0a',
    },
};

export default config;
