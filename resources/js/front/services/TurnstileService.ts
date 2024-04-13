import type { ConfigService } from "./ConfigService";

export class TurnstileService {
    private widgetId: string | undefined;

    constructor(private readonly configService: ConfigService) {
        this.initEvent();
        this.addScript();
    }

    public getToken(): string | null {
        if (!this.widgetId) return null;

        return window.turnstile.getResponse(this.widgetId);
    }

    public refresh(): void {
        if (!this.widgetId) return;

        window.turnstile.reset(this.widgetId);
    }

    private initEvent(): void {
        document.addEventListener("turnstileLoaded", (e) => {
            this.widgetId = window.turnstile.render('#lostplates', {
                sitekey: this.configService.getTurnstileSiteKey(),
                theme: 'auto',
            });
        });
    }

    private addScript(): void {
        const script = document.createElement("script");
        script.src = "https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit";
        script.async = true;
        script.defer = true;
        script.setAttribute('onload', 'document.dispatchEvent(new Event("turnstileLoaded"));');
        document.body.appendChild(script);
    }
}