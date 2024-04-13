import type { MarkerComment } from "./MarkerComment";
import type { MarkerMedia } from "./MarkerMedia";
import type { MarkerType } from "./MarkerType";
import type { Plate } from "./Plate";

export interface Marker {
    id: string;
    type: MarkerType;
    lat: number;
    lng: number;
    formattedAddress: string;
    googleMapsLink: string;
    googlePlaceId: string;
    radius: number;
    additionalInfo: string;
    plateNumber: string;
    phoneNumber: string;
    hasPhoneNumber: boolean;
    isPhoneNumberRevealed: boolean;
    hasEmail: boolean;
    createdAtForHumans: string;
    markerMedia: MarkerMedia[];
    markerComments: MarkerComment[];
    plate: Plate;
}