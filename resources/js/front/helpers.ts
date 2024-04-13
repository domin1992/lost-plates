export const debounce = (callback: any, wait: number = 300) => {
    let timeoutId: number | null = null;
    return (...args: any) => {
        window.clearTimeout(timeoutId as number);
        timeoutId = window.setTimeout(() => {
            callback(...args);
        }, wait);
    };
}