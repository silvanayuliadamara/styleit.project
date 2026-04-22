# styleit.project

## Pembagian Peran Tim
- Silvana Yulia Damara bertugas sebagai GitHub Master.
- Nurul Asyifa bertugas sebagai Tester.
- GitHub Master bertanggung jawab mengelola repository, branch, merge, dan kerapian histori pengembangan.
- Tester bertanggung jawab melakukan pengecekan perubahan sistem dan membantu pengujian.
- GitHub Master dan Tester adalah peran dalam tim, bukan nama branch.

## Branching Strategy
- `main` digunakan untuk versi final dan stabil.
- `development` digunakan untuk integrasi hasil kerja tim.
- Setiap anggota bekerja pada branch masing-masing.
- Hasil kerja dari branch anggota digabung ke `development` terlebih dahulu.
- Jika perubahan di `development` sudah stabil, maka dapat digabung ke `main`.

## Daftar Branch Tim
- `main`
- `development`
- `silvana_yulia_damara`
- `salwa_aprilia`
- `nurul_asyifa`
- `muhammad_abdul_hafiz`

## Aturan Commit Message
Gunakan format:
`tipe: deskripsi singkat`

Jenis commit:
- `feat` = penambahan fitur baru
- `fix` = perbaikan bug
- `refactor` = perubahan struktur tanpa mengubah fungsi
- `docs` = perubahan dokumentasi
- `test` = tambahan atau perubahan testing
- `chore` = pembaruan minor

Contoh:
- `feat: membuat halaman login`
- `fix: memperbaiki error pada view dashboard`
- `docs: menambahkan branching strategy pada README`
- `test: menambahkan test case login`
