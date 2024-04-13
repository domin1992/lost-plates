export interface ApiJsonResponse {
    data: {
        type: string | null;
        id: string;
        attributes: {
            [key: string]: any;
        };
        relationships: {
            [key: string]: {
                data: {
                    type: string;
                    id: string;
                } | {
                    type: string;
                    id: string;
                }[];
            };
        };
    }[];
}