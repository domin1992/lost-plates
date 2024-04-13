export class LocalizationService {
    public getUserLocalization(successCallback: any, errorCallback?: any): void {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }
    }
}