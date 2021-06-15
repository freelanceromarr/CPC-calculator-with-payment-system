<template>
    <app-layout>
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template> -->

        <div class="">
            <div class="w-full mx-auto">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Bidcal/>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Bidcal from '@/Pages/bidcal'

    export default {
        components: {
            AppLayout,
            Bidcal,
        },
    }
</script>
