export interface Pagination {
    total: number;
    count: number;
    perPage: number;
    currentPage: number;
    totalPages: number;
    links: {
        current?: string;
        prev?: string;
        next?: string;
        count?: string;
    }
}