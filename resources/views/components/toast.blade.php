<div
    x-data="toastManager()"
    x-on:toast.window="add($event.detail)"
    class="fixed top-5 right-5 z-[9999] space-y-3 w-[340px] max-w-[90vw]"
>
    <template x-for="t in toasts" :key="t.id">
        <div
            x-show="t.show"
            x-transition.opacity.duration.200ms
            class="rounded-xl border bg-white shadow-lg overflow-hidden"
            :style="{
                borderColor: `color-mix(in oklab, var(--${t.type}), white 70%)`
            }"
        >
            <div class="p-4 flex gap-3 items-start">
                {{-- Icon --}}
                <div class="mt-0.5">
                    <span
                        class="text-lg"
                        :style="{ color: `var(--${t.type})` }"
                        x-text="{
                            success: '✅',
                            error: '❌',
                            info: 'ℹ️',
                            warning: '⚠️'
                        }[t.type]"
                    ></span>
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-900" x-text="t.title"></p>
                    <p class="text-sm text-slate-600 mt-1" x-text="t.message"></p>
                </div>

                {{-- Close --}}
                <button
                    type="button"
                    class="text-slate-400 hover:text-slate-700 transition"
                    @click="remove(t.id)"
                >
                    ✕
                </button>
            </div>

            {{-- Progress bar --}}
            <div class="h-1 bg-slate-100">
                <div
                    class="h-1 transition-all"
                    :style="`
                        width: ${t.progress}%;
                        background-color: var(--${t.type});
                    `"
                ></div>
            </div>
        </div>
    </template>

    <script>
        function toastManager() {
            return {
                toasts: [],
                add(payload) {
                    const id = Date.now() + Math.random()

                    const toast = {
                        id,
                        show: true,
                        type: payload.type ?? 'success',
                        title: payload.title ?? 'Aviso',
                        message: payload.message ?? '',
                        duration: payload.duration ?? 3500,
                        progress: 100,
                    }

                    this.toasts.unshift(toast)

                    const steps = 50
                    const intervalTime = toast.duration / steps
                    let current = 100

                    const interval = setInterval(() => {
                        current -= (100 / steps)
                        toast.progress = Math.max(current, 0)
                    }, intervalTime)

                    setTimeout(() => {
                        clearInterval(interval)
                        this.remove(id)
                    }, toast.duration)
                },
                remove(id) {
                    const index = this.toasts.findIndex(t => t.id === id)
                    if (index === -1) return

                    this.toasts[index].show = false
                    setTimeout(() => {
                        this.toasts.splice(index, 1)
                    }, 180)
                }
            }
        }
    </script>
</div>
