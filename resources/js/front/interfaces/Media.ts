export interface Media {
    id: string;
    userId: string;
    fileType: string;
    imageType: string | null;
    fileName: string;
    fileExtension: string;
    url: { [key: string]: string };
}