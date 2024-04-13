import { defineStore } from "pinia";
import { type Marker } from "../interfaces/Marker";
import { type MarkerType } from "../interfaces/MarkerType";
import { isAxiosError } from "axios";
import { ApiRequestFailedException } from "../exceptions/ApiRequestFailedException";
import { computed, inject, ref } from "vue";
import type { MarkersService } from "../services/MarkersService";
import type { MarkerComment } from "../interfaces/MarkerComment";
import type { MarkerCommentsService } from "../services/MarkerCommentsService";
import type { TurnstileService } from "../services/TurnstileService";

export const useMarkersStore = defineStore('markers', () => {
    const markersService = inject<MarkersService>('markersService');
    const markerCommentsService = inject<MarkerCommentsService>('markerCommentsService');
    const turnstileService = inject<TurnstileService>('turnstileService');

    // Store
    const markers = ref<Marker[]>([]);
    const selectedMarkerId = ref<string | null>(null);

    const selectedMarker = computed<Marker | null>(() => {
        return markers.value.find((marker) => marker.id === selectedMarkerId.value) || null;
    });
    
    // Actions
    const index = async (
        nwLat: number,
        nwLng: number,
        seLat: number,
        seLng: number
    ): Promise<void> => {
        try {
            if (!markersService) return;

            const response = await markersService.index(nwLat, nwLng, seLat, seLng);

            markers.value = response.data.data;
        } catch (e) {
            markers.value = [];
        }
    };

    const store = async (
        type: MarkerType,
        plateNumber: string,
        lat: number,
        lng: number,
        radius: string,
        phoneNumber: string,
        email: string,
        additionalInfo: string,
        notifyWhenFound: boolean
    ): Promise<{message: string; marker: Marker} | null> => {
        try {
            if (!markersService) return null;

            const cfTurnstileResponse = turnstileService?.getToken();
            if (!cfTurnstileResponse) return null;

            const response = await markersService.store(
                type,
                plateNumber,
                lat,
                lng,
                radius,
                phoneNumber,
                email,
                additionalInfo,
                notifyWhenFound,
                cfTurnstileResponse
            );

            turnstileService?.refresh();

            markers.value = [...markers.value, response.data.marker];

            return response.data;
        } catch (e) {
            turnstileService?.refresh();

            if (isAxiosError(e) && e.response?.status === 422) {
                throw new ApiRequestFailedException(e.response.data);
            }

            throw e;
        }
    };

    const storeMarkerComment = async (
        markerId: string,
        name: string,
        content: string
    ): Promise<{message: string, markerComment: MarkerComment} | null> => {
        try {
            if (!markerCommentsService) return null;

            const cfTurnstileResponse = turnstileService?.getToken();
            if (!cfTurnstileResponse) return null;

            const response = await markerCommentsService.store(markerId, name, content, cfTurnstileResponse);

            turnstileService?.refresh();

            markers.value = markers.value.map((marker) => {
                if (marker.id === markerId) {
                    marker.markerComments = [...marker.markerComments, response.data.markerComment];
                }

                return marker;
            });

            return response?.data || null;
        } catch (e) {
            turnstileService?.refresh();

            if (isAxiosError(e) && e.response?.status === 422) {
                throw new ApiRequestFailedException(e.response.data);
            }

            throw e;
        }
    }

    const selectMarker = (markerId: string | null): void => {
        selectedMarkerId.value = markerId;
    };

    const phoneNumber = async (markerId: string): Promise<string | null> => {
        try {
            if (!markersService) return null;

            const cfTurnstileResponse = turnstileService?.getToken();
            if (!cfTurnstileResponse) return null;

            const response = await markersService?.phoneNumber(markerId, cfTurnstileResponse);

            turnstileService?.refresh();

            if (!response?.data.phoneNumber) return null;

            markers.value = markers.value.map((marker: Marker) => {
                if (marker.id === markerId) {
                    marker.phoneNumber = response.data.phoneNumber;
                    marker.isPhoneNumberRevealed = true;
                }

                return marker;
            });

            return response.data.phoneNumber;
        } catch (e) {
            turnstileService?.refresh();

            if (isAxiosError(e) && e.response?.status === 422) {
                throw new ApiRequestFailedException(e.response.data);
            }

            throw e;
        }
    };

    const submitContact = async (
        markerId: string,
        contact: string
    ): Promise<string | null> => {
        try {
            if (!markersService) return null;

            const cfTurnstileResponse = turnstileService?.getToken();
            if (!cfTurnstileResponse) return null;

            const response = await markersService?.submitContact(markerId, contact, cfTurnstileResponse);

            turnstileService?.refresh();

            return response?.data.message || null;
        } catch (e) {
            turnstileService?.refresh();

            if (isAxiosError(e) && e.response?.status === 422) {
                throw new ApiRequestFailedException(e.response.data);
            }

            throw e;
        }
    };

    return {
        markers,
        selectedMarker,

        index,
        store,
        selectMarker,
        storeMarkerComment,
        phoneNumber,
        submitContact,
    };
});