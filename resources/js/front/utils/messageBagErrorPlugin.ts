import type { App, Plugin } from "vue";
import type { MessageBagError } from "../interfaces/MessageBagError"

export const MessageBagErrorPlugin: Plugin<[]> = {
    install: (app: App<any>) => {
        app.config.globalProperties.$messageBagFirstError = (
            messageBagError: MessageBagError,
            key: string
        ): string => {
            if (
                messageBagError?.errors == null
                || messageBagError.errors[key] == null
                || messageBagError.errors[key].length === 0
                || messageBagError.errors[key][0] == null
            ) {
                return '';
            }

            return messageBagError.errors[key][0];
        };

        app.config.globalProperties.$messageBagMessage = (
            messageBagError: MessageBagError
        ): string => {
            if (messageBagError?.message == null) {
                return '';
            }

            return messageBagError.message;
        };
    },
}