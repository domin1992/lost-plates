import { trans } from 'laravel-vue-i18n';
import Toastify from 'toastify-js';

export class NotificationService {
    public success(
        message: string,
        translate: boolean = false,
        destination: string | null = null,
        newWindow: boolean = false
    ): void {
        this.showToast(message, translate, '#28A745', destination, newWindow);
    }

    public error(
        message: string,
        translate: boolean = false,
        destination: string | null = null,
        newWindow: boolean = false
    ): void {
        this.showToast(message, translate, '#DC3545', destination, newWindow);
    }

    public info(
        message: string,
        translate: boolean = false,
        destination: string | null = null,
        newWindow: boolean = false
    ): void {
        this.showToast(message, translate, '#007BFF', destination, newWindow);
    }

    public warning(
        message: string,
        translate: boolean = false,
        destination: string | null = null,
        newWindow: boolean = false
    ): void {
        this.showToast(message, translate, '#007BFF', destination, newWindow);
    }

    private showToast(
        message: string,
        translate: boolean = false,
        backgroundColor: string,
        destination: string | null = null,
        newWindow: boolean = false
    ): void {
        const config = {
            text: translate
                ? trans(message)
                : message,
            duration: 3000,
            destination,
            newWindow,
            close: true,
            gravity: 'top',
            position: 'right',
            stopOnFocus: true,
            style: {
                background: backgroundColor,
            },
        };

        Toastify(config as any).showToast();
    }
}