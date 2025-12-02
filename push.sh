BRANCH_NAME="main" 
COMMIT_MESSAGE="Deployment: Auto-push $(date +%Y-%m-%d\ %H:%M:%S)"

echo ">>> Mempersiapkan Deployment Cepat..."

echo ">>> Menambahkan semua perubahan (git add .)"
git add .

if git status --porcelain | grep -q '^[MARCU]'; then

    echo ">>> Melakukan commit dengan pesan: $COMMIT_MESSAGE"
    git commit -m "$COMMIT_MESSAGE"

    if [ $? -ne 0 ]; then
        echo "❌ GAGAL: Proses commit tidak berhasil. Periksa status Git Anda."
        exit 1
    fi

    echo ">>> Melakukan push ke branch $BRANCH_NAME..."
    git push origin "$BRANCH_NAME"

    if [ $? -eq 0 ]; then
        echo "✅ **DEPLOYMENT LOKAL BERHASIL!** Code sudah di-push ke Git."
    else
        echo "❌ GAGAL: Push ke remote gagal! Periksa koneksi atau status Git Anda."
    fi
else
    echo "✅ Tidak ada perubahan baru yang terdeteksi untuk di-commit. Proses dihentikan."
fi