<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <x-filament::input.wrapper
        :disabled="$isDisabled()"
        :valid="!$errors->has($getStatePath())"
        :attributes="
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class(['sts-fi-file-uploader'])
        "
    >
        <div
            wire:ignore
            x-data="fileUploaderComponent({
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }},

                uploadEndpoint: '{{ $getUploadEndpoint() }}',
                successEndpoint: '{{ $getSuccessEndpoint() }}',
                deleteEndpoint: '{{ $getDeleteEndpoint() }}',

                uploadingMessage: @js($getUploadingMessage()),

                restrictions: @js($getRestrictions()),
            })"

            x-bind:class="{
                'ring-primary-500': dragDepth > 0,
                'divide-y divide-gray-200 dark:divide-white/10': internalState.filled(),
            }"

            x-on:dragenter.prevent.stop="dragDepth++"
            x-on:dragleave.prevent.stop="dragDepth--"
            x-on:dragover.prevent.stop=""
            x-on:drop.prevent.stop="handleDrop"
        >
            <table
                x-show="internalState.filled()"
                class="w-full table-auto divide-y divide-gray-200 dark:divide-white/5"
            >
                <thead>
                    <tr>
                        <th
                            scope="col"
                            class="w-1/2 px-3 py-2 text-start text-sm font-medium text-gray-700 dark:text-gray-200"
                        >
                            {{ $getFileLabel() }}
                        </th>

                        <th
                            scope="col"
                            class="w-1/12 px-3 py-2 text-start text-sm font-medium text-gray-700 dark:text-gray-200"
                        >
                            {{ $getSizeLabel() }}
                        </th>

                        <th
                            scope="col"
                            class="w-5/12"
                        ></th>

                        <th
                            scope="col"
                            class="w-0"
                        ></th>
                    </tr>
                </thead>

                <tbody>
                    <template x-for="file in internalState.files" :key="file.id">
                        <tr>
                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                <span x-text="file.name"></span>
                            </td>

                            <td
                                class="px-3 py-2 text-sm text-gray-700 dark:text-gray-200"
                                colspan="2"
                            >
                                <span
                                    x-show="!file.error"
                                    x-text="file.humanSize"
                                ></span>
                                <span
                                    x-show="file.error"
                                    class="text-red-500"
                                >{{ $getErrorLabel() }}</span>
                            </td>

                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                <div
                                    class="flex gap-2 items-center"
                                    x-bind:style="file.completed && {opacity: 0}"
                                >
                                    <div class="dark:bg-gray-900 bg-gray-100 rounded shadow-sm flex-grow" style="height: 6px">
                                        <div
                                            class="bg-primary-500 h-full rounded-md"
                                            x-bind:class="{
                                                'bg-primary-500' : !file.error,
                                                'bg-red-500' : file.error,
                                                'transition-all' : internalState.length <= 2000,
                                            }"
                                            x-bind:style="'width: ' + file.percentage + '%'"
                                        ></div>
                                    </div>

                                    <div class="flex-shrink-0 tabular-nums">
                                        <span x-text="file.percentage + '%'"></span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                <div class="flex gap-4 items-center">

                                    <template x-if="file.completed && file.url && {{ $getShowOpenLinks() ? 'true' : 'false' }}">
                                        <a
                                            class="inline-flex items-center gap-1 font-semibold hover:underline cursor-pointer text-sm text-green-600 dark:text-green-400 px-2 py-1 rounded"
                                            x-bind:href="file.url"
                                            target="_blank"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                                            </svg>
                                            {{ $getOpenLabel() }}
                                        </a>
                                    </template>

                                    <span
                                        class="inline-flex items-center gap-1 font-semibold hover:underline cursor-pointer text-sm text-red-600 dark:text-red-400 px-2 py-1 rounded"
                                        x-on:click="removeFile(file.id)"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="file.completed ? '{{ $getRemoveLabel() }}' : '{{ $getCancelLabel() }}'"></span>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div
                x-show="internalState.empty() || (!internalState.maxReached() && internalState.empty())"
                x-on:click="$refs.fileInput.click()"
                class="align-middle cursor-pointer flex flex-col items-center px-3 py-2"
            >
                @if(!empty($getEmptyIcon()))
                    <div>
                        <x-filament::icon
                            :icon="$getEmptyIcon()"
                            wire:target="search"
                            class="h-32 text-gray-500 dark:text-gray-400"
                        />
                    </div>
                @endif

                @if(!empty($getEmptyMessage()))
                    <div class="font-semibold text-sm text-gray-700 dark:text-gray-200">
                        {{ $getEmptyMessage() }}
                    </div>
                @endif
            </div>

            <div
                x-show="internalState.filled() && !internalState.maxReached()"
                class="flex justify-center px-3 py-2"
            >
                <button
                    x-on:click="$refs.fileInput.click()"
                    class="fi-link group/link relative inline-flex items-center justify-center outline-none fi-size-md fi-link-size-md gap-1.5  fi-color-gray fi-ac-action fi-ac-link-action"
                    type="button"
                    wire:loading.attr="disabled"
                >
                    <span class="font-semibold group-hover/link:underline group-focus-visible/link:underline text-sm text-gray-700 dark:text-gray-200">
                        {{ $getAddFilesLabel() }}
                    </span>
                </button>
            </div>

            <input type="file"
                multiple
                x-ref="fileInput"
                class="hidden"
                x-on:change="([...event.target.files]).forEach((file) => {
                   uppy.addFile({
                       source: 'file input',
                       name: file.name,
                       type: file.type,
                       data: file,
                   })
                })"
            />
        </div>
    </x-filament::input.wrapper>
</x-dynamic-component>
