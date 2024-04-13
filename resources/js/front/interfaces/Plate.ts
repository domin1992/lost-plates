import type { Marker } from "./Marker";

export interface Plate {
    id: string;
    number: string;
    markers?: Marker[];
};