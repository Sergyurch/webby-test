<h1 class="text-center mb-3">Список фільмів</h1>
<div class="mb-3 row align-items-center">
    <div class="text-left col-12 col-md-7 col-lg-4 d-flex mt-3">
        <input id="search-value" type="text" class="form-control">
        <select id="search-by" class="form-control ml-1 w-auto">
            <option value="title">за назвою</option>
            <option value="actor">за актором</option>
        </select>
        <button id="search-film-btn" class="btn btn-primary ml-1" title="Пошук фільмів">Пошук</button>
    </div>
    <div class="text-right col-12 col-md-5 col-lg-8 mt-3">
        <a href="/film/add" class="btn btn-success" title="Додати новий фільм">+</a>
        <input type="file" id="input-file" class="d-none">
        <button id="import-film-btn" class="btn btn-success" title="Імпортувати фільми з файлу">Імпортувати</button>
        <button id="delete-film-btn" class="btn btn-danger" title="Видалити обрані фільми" disabled>Видалити</button>
    </div>
</div>
<table id="films" class="table table-bordered table-hover">
    <thead class="thead-dark">
        <tr>
            <th><input type="checkbox" id="checkAll"></th>
            <th class="text-center">Назва</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($films as $film): ?>
            <tr>
                <td><input type="checkbox" data-id="<?= $film['id']; ?>"></td>
                <td><a href="/film/show/<?= $film['id']; ?>"><?= $film['title']; ?></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
