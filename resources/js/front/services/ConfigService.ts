export class ConfigService {
    private allowedLanguages: {[key: string]: string} = {};

    constructor(
        private readonly googleCloudApiKey: string,
        private readonly turnstileSiteKey: string
    ) {
        this.loadFromGlobalVariables();
    }

    public getGoogleCloudApiKey(): string {
        return this.googleCloudApiKey;
    }

    public getTurnstileSiteKey(): string {
        return this.turnstileSiteKey;
    }

    public getAllowedLanguages(): {[key: string]: string} {
        return this.allowedLanguages;
    }

    public getAllowedLanguagesKeys(): string[] {
        return Object.keys(this.allowedLanguages);
    }

    private loadFromGlobalVariables(): void {
        this.allowedLanguages = window.globalVariables.allowedLocale;
    }
}