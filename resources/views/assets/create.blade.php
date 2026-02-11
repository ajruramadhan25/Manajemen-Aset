<x-app-layout>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Aset Baru</h5>
            <small class="text-muted float-end">Input data aset dengan lengkap</small>
        </div>
        <div class="card-body">
            <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Nama Aset</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Laptop Dell XPS 15"
                        value="{{ old('name') }}" required />
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="asset_code">Kode Aset</label>
                        <input type="text" class="form-control" id="asset_code" name="asset_code" placeholder="AST-001"
                            value="{{ old('asset_code') }}" required />
                        @error('asset_code') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="quantity">Jumlah Unit</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="1"
                            value="{{ old('quantity', 1) }}" min="1" required />
                        @error('quantity') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div id="unit-fields-container" class="mb-3">
                    <!-- Unit identifier fields will be inserted here dynamically -->
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="parent_select">Kategori Utama</label>
                        <select id="parent_select" name="parent_category_id" class="form-select" required onchange="loadSubcategories()">
                            <option value="">Pilih Kategori Utama</option>
                            @foreach($categories->where('parent_category_id', null) as $category)
                                <option value="{{ $category->id }}" {{ old('parent_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="subcategory_id">Sub Kategori (Opsional)</label>
                        <select id="subcategory_id" class="form-select">
                            <option value="">Pilih Sub Kategori atau Gunakan Parent</option>
                        </select>
                        @error('category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="brand_id">Brand (Opsional)</label>
                        <div class="input-group">
                            <select id="brand_id" name="brand_id" class="form-select">
                                <option value="">Pilih Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                                <i class="bx bx-plus"></i>
                            </button>
                        </div>
                        @error('brand_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Hidden field for category_id - will be populated by JavaScript -->
                <input type="hidden" id="category_id" name="category_id" value="{{ old('category_id') }}" />

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="purchase_date">Tanggal Pembelian</label>
                        <input type="date" class="form-control" id="purchase_date" name="purchase_date"
                            value="{{ old('purchase_date', date('Y-m-d')) }}" required />
                        @error('purchase_date') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="status">Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available
                            </option>
                            <option value="deployed" {{ old('status') == 'deployed' ? 'selected' : '' }}>Deployed</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance
                            </option>
                            <option value="broken" {{ old('status') == 'broken' ? 'selected' : '' }}>Broken</option>
                        </select>
                        @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="useful_life">Masa Manfaat (Tahun)</label>
                        <input type="number" class="form-control" id="useful_life" name="useful_life" placeholder="5"
                            value="{{ old('useful_life', 5) }}" min="1" max="100" required />
                        @error('useful_life') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="price">Harga (Rp)</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="price" name="price" placeholder="15000000"
                            value="{{ old('price') }}" required />
                        <span class="input-group-text">.00</span>
                    </div>
                    @error('price') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="image">Gambar Aset (Opsional)</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" />
                    @error('image') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Aset</button>
                <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>

    <script>
        const categoryData = @json($categories->where('parent_category_id', '!=', null)->groupBy('parent_category_id'));

        function loadSubcategories() {
            const parentId = document.getElementById('parent_select').value;
            const subcategorySelect = document.getElementById('subcategory_id');
            subcategorySelect.innerHTML = '<option value="">Pilih Sub Kategori atau Gunakan Parent</option>';

            if (parentId && categoryData[parentId]) {
                categoryData[parentId].forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.text = sub.name;
                    subcategorySelect.appendChild(option);
                });
            }
        }

        // When subcategory is selected, update the hidden category_id field
        document.getElementById('subcategory_id').addEventListener('change', function() {
            const categoryId = document.getElementById('category_id');
            if (this.value) {
                categoryId.value = this.value;  // Subcategory selected
            } else {
                categoryId.value = '';  // Clear if unselected
            }
        });

        // Form submission: handle category fallback
        document.querySelector('form').addEventListener('submit', function(e) {
            const categoryId = document.getElementById('category_id');
            const parentId = document.getElementById('parent_select');

            // If no subcategory selected, use parent as category_id
            if (!categoryId.value && parentId.value) {
                categoryId.value = parentId.value;
            }
        });

        const quantityInput = document.getElementById('quantity');
        const containerDiv = document.getElementById('unit-fields-container');

        function renderUnitFields() {
            const qty = parseInt(quantityInput.value) || 0;
            containerDiv.innerHTML = '';

            if (qty > 0) {
                const heading = document.createElement('label');
                heading.className = 'form-label d-block mb-2';
                heading.textContent = `Masukkan ID/Plat Nomor untuk ${qty} Unit`;
                containerDiv.appendChild(heading);

                const rowDiv = document.createElement('div');
                rowDiv.className = 'row';

                for (let i = 1; i <= qty; i++) {
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-md-4 mb-2';

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'form-control form-control-sm';
                    input.name = `unit_identifiers[${i}]`;
                    input.placeholder = `Unit ${i} (mis: AB 1234 XY)`;

                    colDiv.appendChild(input);
                    rowDiv.appendChild(colDiv);
                }

                containerDiv.appendChild(rowDiv);
            }
        }

        // Initialize on page load
        renderUnitFields();
        loadSubcategories();

        // Listen to quantity changes
        quantityInput.addEventListener('change', renderUnitFields);
        quantityInput.addEventListener('input', renderUnitFields);
    </script>

    <!-- Modal Tambah Brand -->
    <div class="modal fade" id="addBrandModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Brand Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="newBrandName">Nama Brand</label>
                        <input type="text" class="form-control" id="newBrandName" placeholder="contoh: Samsung">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="newBrandDesc">Deskripsi</label>
                        <textarea class="form-control" id="newBrandDesc" rows="3" placeholder="Deskripsi brand..."></textarea>
                    </div>
                    <div id="brandErrorMsg" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="createBrandBtn">Simpan Brand</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('createBrandBtn').addEventListener('click', async function() {
            const name = document.getElementById('newBrandName').value.trim();
            const description = document.getElementById('newBrandDesc').value.trim();
            const errorMsg = document.getElementById('brandErrorMsg');

            if (!name) {
                errorMsg.textContent = 'Nama brand harus diisi';
                errorMsg.classList.remove('d-none');
                return;
            }

                try {
                const response = await fetch('{{ route('brands.store') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        name: name,
                        description: description,
                    })
                });

                if (response.ok) {
                    const brand = await response.json();
                    
                    // Add to dropdown
                    const select = document.getElementById('brand_id');
                    const option = document.createElement('option');
                    option.value = brand.id;
                    option.textContent = brand.name;
                    option.selected = true;
                    select.appendChild(option);

                    // Close modal & clear form
                    bootstrap.Modal.getInstance(document.getElementById('addBrandModal')).hide();
                    document.getElementById('newBrandName').value = '';
                    document.getElementById('newBrandDesc').value = '';
                    errorMsg.classList.add('d-none');
                } else {
                    const error = await response.json();
                    errorMsg.textContent = error.message || 'Gagal menambah brand';
                    errorMsg.classList.remove('d-none');
                }
            } catch (error) {
                errorMsg.textContent = 'Error: ' + error.message;
                errorMsg.classList.remove('d-none');
            }
        });
    </script>
</x-app-layout>