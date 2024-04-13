import type { AxiosPromise } from "axios";
import { ApiService } from "./ApiService";
import type { Plate } from "../interfaces/Plate";

export class PlatesService extends ApiService {
    public async show(id: string): AxiosPromise<{plate: Plate}> {
        return await this.axios.get<{plate: Plate}>(`plate/${id}`);
    }

    public async showByNumber(number: string): AxiosPromise<{plate: Plate}> {
        return await this.axios.get<{plate: Plate}>(`plate/number/${number}`);
    }
}