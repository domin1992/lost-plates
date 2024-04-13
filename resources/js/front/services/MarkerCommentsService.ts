import type { AxiosPromise } from "axios";
import { ApiService } from "./ApiService";
import type { MarkerComment } from "../interfaces/MarkerComment";

export class MarkerCommentsService extends ApiService {
    public async index(
        markerId: string
    ): AxiosPromise<{markerComments: MarkerComment[]}> {
        return this.axios.get<{markerComments: MarkerComment[]}>(`markers/${markerId}/marker-comments`);
    }

    public async store(
        markerId: string,
        name: string,
        content: string,
        cfTurnstileResponse: string
    ): AxiosPromise<{message: string; markerComment: MarkerComment}> {
        return this.axios
            .post<{message: string; markerComment: MarkerComment}>(
                `markers/${markerId}/marker-comments`,
                {
                    name,
                    content,
                    cfTurnstileResponse,
                }
            );
    }
}