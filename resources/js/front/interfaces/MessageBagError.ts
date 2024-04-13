export interface MessageBagError {
    message?: string;
    errors?: {
        [key: string]: string[];
    };
}