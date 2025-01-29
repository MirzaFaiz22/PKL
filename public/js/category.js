document.addEventListener("DOMContentLoaded", function () {
    const data = [
        {
            id: "100643",
            name: "Books & Magazines",
            parentId: "0",
            children: [
                {
                    id: "100777",
                    name: "Books",
                    parentId: "100643",
                    children: [
                        {
                            id: "101551",
                            name: "Language Learning & Dictionaries",
                            parentId: "100777",
                            children: [],
                        },
                        {
                            id: "101560",
                            name: "Science & Maths",
                            parentId: "100777",
                            children: [],
                        },
                    ],
                },
                {
                    id: "100778",
                    name: "E-Books",
                    parentId: "100643",
                    children: [],
                },
                {
                    id: "100779",
                    name: "Others",
                    parentId: "100643",
                    children: [],
                },
            ],
        },
    ];

    const dropdownSelect = document.getElementById("categorySelect");
    const dropdownMenu = document.getElementById("categoryMenu");
    const fullCategoryId = document.getElementById("fullCategoryId");
    const categoryDropdown = document.querySelector('.category-dropdown');

    let selectedIds = [];

    function renderMenu(categories) {
        dropdownMenu.innerHTML = ""; // Hapus isi dropdown sebelumnya
        categories.forEach((category) => {
            const li = document.createElement("li");
            li.textContent = category.name;
            li.dataset.id = category.id;
            li.dataset.children = JSON.stringify(category.children);
            dropdownMenu.appendChild(li);

            li.addEventListener("click", function (e) {
                e.stopPropagation();
                const categoryId = li.dataset.id;
                const categoryName = li.textContent;
                const children = JSON.parse(li.dataset.children);

                selectedIds.push(categoryId);
                dropdownSelect.querySelector("span").textContent = categoryName;

                if (children.length > 0) {
                    renderMenu(children); // Tampilkan child category
                } else {
                    dropdownMenu.style.display = "none"; // Tutup jika tidak ada child
                    fullCategoryId.value = JSON.stringify(selectedIds); // Simpan ID terpilih
                    
                    // Tambahkan validasi
                    fullCategoryId.dispatchEvent(new Event('input'));
                    
                    console.log("Selected Full Category IDs:", selectedIds);
                }
            });
        });

        dropdownMenu.style.display = "block"; // Tampilkan menu
    }

    // Tampilkan parent categories saat pertama kali klik
    dropdownSelect.addEventListener("click", function (e) {
        e.stopPropagation();
        selectedIds = []; // Reset selection
        renderMenu(data);
    });

    // Tutup dropdown jika klik di luar
    document.addEventListener("click", function () {
        dropdownMenu.style.display = "none";
    });

    // Tambahkan validasi form submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (!fullCategoryId.value) {
            e.preventDefault();
            alert('Silakan pilih kategori');
            dropdownSelect.focus();
        }
    });

    // Custom validation message
    fullCategoryId.addEventListener('invalid', function() {
        this.setCustomValidity('Silakan pilih kategori');
    });

    fullCategoryId.addEventListener('input', function() {
        this.setCustomValidity('');
    });
});

