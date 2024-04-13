import type { AxiosInstance } from "axios";

export class ApiService {
    constructor(protected readonly axios: AxiosInstance) {}
}