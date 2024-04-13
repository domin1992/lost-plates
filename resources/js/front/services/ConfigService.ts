export class ConfigService {
    constructor(
        private readonly googleCloudApiKey: string,
        private readonly turnstileSiteKey: string
    ) {}

    public getGoogleCloudApiKey(): string {
        return this.googleCloudApiKey;
    }

    public getTurnstileSiteKey(): string {
        return this.turnstileSiteKey;
    }

    public allowedLanguages(): string[] {
        return [
            'pl',
        ];
    }
}