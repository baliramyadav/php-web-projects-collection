 <ul class="nav nav-tabs">
    <li role="presentation" class="<?=(($get_url == 'index') || ($get_url == null)) ? 'active' : ''?>">
        <a href="#" title="Check List">
            <span class="round-tab">
                <i class="fa fa-list"></i>
            </span>
        </a>
    </li>
    <li role="presentation" class="<?=(($get_url == 'purchase')) ? 'active' : ''?>">
        <a href="#" title="Purchase">
            <span class="round-tab">
                <i class="fa fa-shield"></i>
            </span>
        </a>
    </li>
    <li role="presentation" class="<?=(($get_url == 'database')) ? 'active' : ''?>">
        <a href="#" title="Database">
            <span class="round-tab">
                <i class="fa fa-database"></i>
            </span>
        </a>
    </li>
    <li role="presentation" class="<?=(($get_url == 'setting')) ? 'active' : ''?>">
        <a href="#" title="Setting">
            <span class="round-tab">
                <i class="fa fa-cogs"></i>
            </span>
        </a>
    </li>
    <li role="presentation" class="<?=(($get_url == 'complate')) ? 'active' : ''?>">
        <a href="#" title="Complete">
            <span class="round-tab">
                <i class="glyphicon glyphicon-ok"></i>
            </span>
        </a>
    </li>
</ul>