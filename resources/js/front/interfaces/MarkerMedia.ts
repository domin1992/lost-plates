import type { Media } from "./Media";

export interface MarkerMedia {
    id: string;
    markerId: string;
    mediaId: string;
    media: Media;
}