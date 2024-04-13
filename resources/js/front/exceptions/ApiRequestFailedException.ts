import type { MessageBagError } from "../interfaces/MessageBagError";

export class ApiRequestFailedException extends Error {
    public constructor(
        protected readonly messageBagError: MessageBagError
    ) {
        super(messageBagError.message);
    }

    public getMessageBagError(): MessageBagError {
        return this.messageBagError;
    }
}