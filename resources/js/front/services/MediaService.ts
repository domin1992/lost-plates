import type { AxiosPromise } from "axios";
import { ApiService } from "./ApiService";
import type { Media } from "../interfaces/Media";

export class MediaService extends ApiService {
    public async store(
        file: File,
        type: string,
        imageType: string,
        cfTurnstileResponse: string
    ): AxiosPromise<{message: string, media: Media}> {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type);
        formData.append('imageType', imageType);
        formData.append('cfTurnstileResponse', cfTurnstileResponse);

        return await this.axios.post<{message: string, media: Media}>('media', formData);
    }

    public async destroy(id: string): AxiosPromise<{message: string}> {
        return await this.axios.post<{message: string}>(`media/${id}`, {
            _method: 'DELETE'
        });
    }

    public async show(id: string): AxiosPromise<{media: Media}> {
        return await this.axios.get<{media: Media}>(`media/${id}`);
    }
}