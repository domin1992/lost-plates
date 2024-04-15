import mitt from 'mitt';
import type { Media } from '@front/interfaces/Media';

export type Events = {
    toggleCreateMarkerModal: { lat: number, lng: number; };
    toggleGalleryPreviewModal: { media: Media[], initialActiveMediaId: string; };
    languageChanged: { lang: string; };
};

export const emitter = mitt<Events>();