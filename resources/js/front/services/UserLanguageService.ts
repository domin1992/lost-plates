import Cookies from 'js-cookie';
import type { ConfigService } from './ConfigService';

export class UserLanguageService {
    constructor(private readonly configService: ConfigService) {}

    private getUseranguageFromBrowser(): string {
        return navigator.language;
    }

    private getUserLanguageFromCookie(): string | undefined {
        return Cookies.get('lang');
    }

    public getUserLanguage(): string {
        const userLanguage = this.getUserLanguageFromCookie()
            || this.getUseranguageFromBrowser();

        if (this.configService.allowedLanguages().includes(userLanguage)) {
            return userLanguage;
        }

        return this.configService.allowedLanguages()[0];
    }
}