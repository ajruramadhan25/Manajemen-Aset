# Sistem Manajemen Unit Aset

## Fitur Utama

### 1. Pembuatan Aset dengan Unit Individual
- Saat membuat aset baru, masukkan **jumlah unit**
- Form akan otomatis menampilkan **field input dinamis** untuk setiap unit
- Masukkan **ID unik** (plat nomor, serial, dll) untuk setiap unit
- Contoh: Motor Beat 5 unit → input plat AB1234XY untuk setiap motor

### 2. Manajemen Unit (CRUD)
- **Daftar Unit**: Lihat semua unit dengan status (Available, Borrowed, Maintenance, Retired)
- **Aksi Cepat**:
  - Set Available / Maintenance / Retire
  - Edit unit (ganti asset, identifier, serial, notes)

### 3. Peminjaman Per-Unit
- Saat checkout aset, pilih **unit spesifik** yang akan dipinjam
- Contoh: Pilih motor dengan plat AB1234XY
- Unit akan ditandai sebagai **Borrowed** otomatis

### 4. Pengembalian Per-Unit (Partial Return)
- Kembalikan **sebagian atau seluruh unit** dari satu pinjaman
- Contoh: Pinjam 5 motor, kembalikan 2 motor terlebih dahulu
- Unit yang dikembalikan otomatis set ke **Available**
- Quantity pinjaman berkurang sesuai unit yang dikembalikan

### 5. Daftar Pinjaman dengan Unit
- Lihat semua pinjaman beserta **unit yang dipinjam**
- Tombol cepat untuk **Return Units** (partial) atau **Return All** (semuanya)

## Alur Kerja

### Membuat Aset
1. Buka **Inventory → Tambah Aset**
2. Isi nama, kode, kategori, harga, dll
3. Masukkan **Jumlah Unit** (mis: 5)
4. Muncul 5 field untuk ID unik setiap motor (plat nomor)
5. Simpan → unit akan otomatis dibuat dengan status **available**

### Meminjamkan Motor
1. Buka detail aset di **Inventory**
2. Klik **Checkout (Pinjamkan)**
3. Pilih karyawan peminjam
4. Masukkan jumlah yang dipinjam (mis: 2)
5. **Pilih unit spesifik** (checkbox motor mana yang dipinjam)
6. Proses → motor dengan plat terpilih status jadi **borrowed**

### Mengembalikan Motor
1. Buka **Peminjaman**
2. Cari pinjaman yang status "Borrowed"
3. Klik **Return Units** (untuk partial/sebagian)
4. Pilih unit mana yang dikembalikan
5. Proses → unit status kembali **available**

Atau klik **Return All** untuk kembalikan semua sekaligus.

## Database Schema

### asset_units
- `id`: Primary key
- `asset_id`: Foreign key ke assets
- `unique_identifier`: ID unik (plat nomor, serial, dll)
- `serial_number`: Serial tambahan
- `status`: available, borrowed, maintenance, retired
- `notes`: Catatan unit

### asset_loan_units
- `id`: Primary key
- `asset_loan_id`: Foreign key ke asset_loans
- `asset_unit_id`: Foreign key ke asset_units
- (Pivot table untuk many-to-many relationship)

## Validasi & Keamanan

- Saat checkout: Validasi unit tersedia & quantity sesuai
- Saat return: Validasi unit belong to loan, tidak ada duplikasi
- Audit log: Setiap CHECKOUT, CHECKIN, CHECKIN_PARTIAL dicatat

## Next Steps (Opsional)

- Export laporan pinjam-kembalikan per unit
- Notifikasi jika unit expired (lama dipinjam)
- Barcode/QR scan untuk checkout cepat
- History lengkap per unit (borrowed by, date, dll)
