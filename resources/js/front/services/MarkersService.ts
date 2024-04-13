import type { AxiosPromise } from "axios";
import { ApiService } from "./ApiService";
import type { Marker } from "../interfaces/Marker";
import type { Meta } from "../interfaces/ApiJsonResponseMeta";
import type { MarkerType } from "../interfaces/MarkerType";

export class MarkersService extends ApiService {
    public async index(
        nwLat: number,
        nwLng: number,
        seLat: number,
        seLng: number
    ): AxiosPromise<{data: Marker[], meta: Meta}> {
        return await this.axios.get<{data: Marker[], meta: Meta}>('markers', {
            params: {
                corners: {
                    nwLat,
                    nwLng,
                    seLat,
                    seLng,
                }
            }
        });
    }

    public async store(
        type: MarkerType,
        plateNumber: string,
        lat: number,
        lng: number,
        radius: string,
        phoneNumber: string,
        email: string,
        additionalInfo: string,
        notifyWhenFound: boolean,
        cfTurnstileResponse: string
    ): AxiosPromise<{ message: string; marker: Marker }> {
        return await this.axios.post<{message: string; marker: Marker}>('markers', {
            type,
            plateNumber,
            lat,
            lng,
            radius,
            phoneNumber,
            email,
            additionalInfo,
            notifyWhenFound,
            cfTurnstileResponse,
        });
    }

    public async show(id: string): AxiosPromise<{marker: Marker}> {
        return await this.axios.get<{marker: Marker}>(`markers/${id}`);
    }

    public async phoneNumber(id: string, cfTurnstileResponse: string): AxiosPromise<{phoneNumber: string}> {
        return await this.axios.get<{phoneNumber: string}>(`markers/${id}/phone-number`, {params: {cfTurnstileResponse}});
    }

    public async submitContact(
        id: string,
        contact: string,
        cfTurnstileResponse: string
    ): AxiosPromise<{message: string}> {
        return await this.axios.post<{message: string}>(`markers/${id}/submit-contact`, {contact, cfTurnstileResponse});
    }
}