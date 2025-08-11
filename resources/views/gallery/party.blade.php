<!-- resources/views/gallery/party.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $party->name }} Gallery
            </h2>
            <button id="addPhotosBtn"
                    class="px-4 py-2 bg-indigo-600 text-white rounded">
                + Add Photos
            </button>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($photos as $photo)
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <img loading="lazy"
                         src="{{ asset('storage/'.$photo->path) }}"
                         class="w-full h-48 object-cover"
                         alt="Photo by {{ $photo->user->name }}">
                    <div class="p-4 text-sm text-gray-600">
                        By {{ $photo->user->name }} · {{ $photo->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        </div>
        <div>{{ $photos->links() }}</div>
    </div>

    <div id="uploadModal"
         class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-[800px] mx-auto flex flex-col" style="width: 800px; max-height: 90vh;">

            <!-- Header -->
            <div class="flex items-center justify-between border-b p-6">
                <h3 class="text-lg font-semibold text-gray-800">Upload Photos</h3>
                <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Main Content -->
            <div class="flex-1 overflow-hidden flex flex-col px-6 pt-4 space-y-6">

                <!-- Select Button -->
                <button id="selectPhotosBtn"
                        class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v-4a4 4 0 014-4h12M12 12l3-3m0 0l3 3m-3-3v12" />
                    </svg>
                    <span>Select Photos</span>
                </button>
                <input type="file" id="fileInput" multiple accept="image/*" class="hidden">

                <!-- Previews Scrollable -->
                <div id="previewsContainer" class="flex-1 overflow-y-auto border border-gray-200 p-2 rounded space-y-3">
                    <!-- Previews will be injected here -->
                </div>

                <!-- Summary -->
                <div id="summary" class="text-sm text-gray-600"></div>
            </div>

            <!-- Footer -->
            <div class="px-6 p-6 space-y-3 border-t">
                <div class="flex items-center gap-4">
                    <button id="uploadBtn"
                            disabled
                            class="flex-1 inline-flex justify-center items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" x-show="!loading" class="h-5 w-5"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        <span>Upload</span>
                    </button>
                    <div id="progressWrapper" class="flex-1 hidden">
                        <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                            <div id="progressBar" class="bg-indigo-600 h-2 transition-all" style="width:0%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addBtn      = document.getElementById('addPhotosBtn');
            const modal       = document.getElementById('uploadModal');
            const closeBtn    = document.getElementById('closeModalBtn');
            const selectBtn   = document.getElementById('selectPhotosBtn');
            const fileInput   = document.getElementById('fileInput');
            const previewsCt  = document.getElementById('previewsContainer');
            const uploadBtn   = document.getElementById('uploadBtn');
            const progressW   = document.getElementById('progressWrapper');
            const progressBar = document.getElementById('progressBar');
            const summaryEl   = document.getElementById('summary');

            const partyYear = new Date('{{ $party->start_date }}').getFullYear();
            let previews = [];

            addBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });
            closeBtn.addEventListener('click', () => {
                if (!uploadBtn.disabled) { // prevent closing mid-upload
                    modal.classList.add('hidden');
                    previews = []; renderPreviews();
                    summaryEl.textContent = '';
                    progressW.classList.add('hidden');
                    progressBar.style.width = '0%';
                }
            });
            modal.addEventListener('click', e => {
                if (e.target === modal && !uploadBtn.disabled) closeBtn.click();
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden') && !uploadBtn.disabled) {
                    closeBtn.click();
                }
            });

            selectBtn.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', e => {
                let files = Array.from(e.target.files);
                if (files.length > 20) {
                    alert('Only the first 20 files will be processed.');
                    files = files.slice(0, 20);
                }
                previews = files.map(f => ({
                    file: f,
                    name: f.name,
                    year: new Date(f.lastModified).getFullYear(),
                    url: URL.createObjectURL(f)
                }));
                renderPreviews();
            });

            function renderPreviews() {
                previewsCt.innerHTML = '';
                previews.forEach((p, i) => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center gap-2 p-2 border rounded';
                    div.innerHTML = `
            <img src="${p.url}" class="w-16 h-10 object-cover rounded" alt="thumb">
            <div class="flex-1 text-sm">
              <div class="truncate">${p.name}</div>
              <div class="text-gray-500">${p.year}</div>
            </div>
            ${p.year !== partyYear
                        ? '<div class="text-red-500" title="Photo year mismatch">⚠</div>' : ''}
            <button class="ml-2 text-gray-500">&times;</button>
          `;
                    div.querySelector('button').addEventListener('click', () => {
                        previews.splice(i,1);
                        renderPreviews();
                    });
                    previewsCt.appendChild(div);
                });
                uploadBtn.disabled = previews.length === 0;
            }

            uploadBtn.addEventListener('click', async () => {
                if (!previews.length) return;
                uploadBtn.disabled = true;
                progressW.classList.remove('hidden');
                progressBar.style.width = '0%';
                summaryEl.textContent = '';

                const form = new FormData();
                previews.forEach(p => form.append('photos[]', p.file));
                form.append('_token','{{ csrf_token() }}');

                try {
                    const resp = await fetch(window.location.pathname, {
                        method: 'POST',
                        body: form,
                        headers: { 'Accept': 'application/json' },
                    });
                    const json = await resp.json();

                    const added   = (json.added   || []).length;
                    const skipped = (json.skipped || []).length;
                    const errors  = Object.keys(json.errors || {}).length;

                    progressBar.style.width = '100%';
                    summaryEl.textContent =
                        `${added} added • ${skipped} duplicates skipped • ${errors} errors`;

                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } catch (err) {
                    summaryEl.textContent = 'Upload failed: ' + err.message;
                    uploadBtn.disabled = false;
                }
            });
        });
    </script>
</x-app-layout>
