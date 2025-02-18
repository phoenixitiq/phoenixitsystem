export const config = {
    api: {
        baseURL: process.env.REACT_APP_API_URL || 'http://localhost:3000/api',
        timeout: 10000
    },
    auth: {
        tokenKey: 'auth_token',
        refreshTokenKey: 'refresh_token'
    },
    app: {
        name: 'PhoenuxSys',
        version: '1.0.0',
        defaultLanguage: 'ar',
        supportedLanguages: ['ar', 'en']
    }
};

export default config;
