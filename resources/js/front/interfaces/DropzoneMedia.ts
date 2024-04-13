import type { Media } from "./Media";

export interface DropzoneMedia extends Media {
    position: number;
    uploading: boolean;
    uploadingProgress: number;
}