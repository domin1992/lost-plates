<template>
    <div class="flex justify-center mt-4">
        <nav>
            <ul class="flex list-style-none">
                <li :class="['page-item']" v-if="pagination.current_page != 1">
                    <button
                        type="button"
                        class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-[color,background-color] duration-300 text-gray-800 hover:text-white hover:bg-purple-heart focus:shadow-none"
                        @click="$emit('prevPage')"
                    >
                        <i class="fas fa-long-arrow-alt-left"></i>
                    </button>
                </li>
                <li
                    :class="['page-item']"
                    v-for="page in pages"
                    :key="page"
                >
                    <button
                        type="button"
                        :class="[
                            'page-link relative block py-1.5 px-3 rounded border-0 outline-none transition-[color,background-color] duration-300 hover:text-white hover:bg-purple-heart focus:shadow-none',
                            page == pagination.current_page ? 'bg-purple-heart text-white' : 'bg-transparent text-gray-800'
                        ]"
                        @click="$emit('selectPage', page)"
                    >
                        {{ page }}
                    </button>
                </li>
                <li :class="['page-item']" v-if="pagination.current_page != pagination.total_pages">
                    <button
                        type="button"
                        class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-[color,background-color] duration-300 text-gray-800 hover:text-white hover:bg-purple-heart focus:shadow-none"
                        @click="$emit('nextPage')"
                    >
                        <i class="fas fa-long-arrow-alt-right"></i>
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    props: ['pagination'],
    computed: {
        pages() {
            let pages = [
                this.pagination.current_page,
            ];

            for (let i = 1; i < 3; i++) {
                if (this.pagination.current_page - i > 0) {
                    pages.push(this.pagination.current_page - i);
                }

                if (this.pagination.current_page + i <= this.pagination.total_pages) {
                    pages.push(this.pagination.current_page + i);
                }
            }

            pages.sort();

            return pages;
        }
    },
}
</script>
