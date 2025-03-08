<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>

                <li class=" {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="bi bi-house fs-5"></i>
                        <span>ទំព័រដើម</span>
                        <span class="menu-arrow"></span>
                    </a>
                </li>




                @canany(['បញ្ជីការអនុញ្ញាត', 'បង្កើតការអនុញ្ញាត'])
                    <li
                        class="submenu {{ request()->routeIs('permissions.index') || request()->is('permissions/*/edit') || request()->routeIs('permissions.create') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-shield-lock fs-5"></i>
                            <span>ការអនុញ្ញាត</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('បញ្ជីការអនុញ្ញាត')
                                <li>
                                    <a href="{{ route('permissions.index') }}"
                                        class="{{ request()->routeIs('permissions.index') || request()->is('permissions/*/edit') ? 'active' : '' }}">
                                        បញ្ជីការអនុញ្ញាត
                                    </a>
                                </li>
                            @endcan
                            @can('បង្កើតការអនុញ្ញាត')
                                <li>
                                    <a href="{{ route('permissions.create') }}"
                                        class="{{ request()->routeIs('permissions.create') ? 'active' : '' }}">
                                        បង្កើតការអនុញ្ញាត
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['បញ្ជីតួនាទីអ្នកប្រើប្រាស់', 'បង្កើតតួនាទីអ្នកប្រើប្រាស់'])
                    <li
                        class="submenu {{ request()->routeIs('roles.index') || request()->is('roles/*/edit') || request()->routeIs('roles.create') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-gear fs-5"></i>
                            <span>តួនាទីអ្នកប្រើប្រាស់</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('បញ្ជីតួនាទីអ្នកប្រើប្រាស់')
                                <li>
                                    <a href="{{ route('roles.index') }}"
                                        class="{{ request()->routeIs('roles.index') || request()->is('roles/*/edit') ? 'active' : '' }}">
                                        បញ្ជីតួនាទីអ្នកប្រើប្រាស់
                                    </a>
                                </li>
                            @endcan
                            @can('បង្កើតតួនាទីអ្នកប្រើប្រាស់')
                                <li>
                                    <a href="{{ route('roles.create') }}"
                                        class="{{ request()->routeIs('roles.create') ? 'active' : '' }}">
                                        បង្កើតតួនាទីអ្នកប្រើប្រាស់
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany







                @canany(['បញ្ជីអ្នកប្រើប្រាស់', 'បង្កើតអ្នកប្រើប្រាស់'])
                    <li
                        class="submenu {{ request()->routeIs('users.index') || request()->is('users/*/edit') || request()->routeIs('users.create') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-people fs-5"></i>
                            <span>អ្នកប្រើប្រាស់</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('បញ្ជីអ្នកប្រើប្រាស់')
                                <li>
                                    <a href="{{ route('users.index') }}"
                                        class="{{ request()->routeIs('users.index') || request()->is('users/*/edit') ? 'active' : '' }}">
                                        បញ្ជីអ្នកប្រើប្រាស់
                                    </a>
                                </li>
                            @endcan
                            @can('បង្កើតអ្នកប្រើប្រាស់')
                                <li>
                                    <a href="{{ route('users.create') }}"
                                        class="{{ request()->routeIs('users.create') ? 'active' : '' }}">
                                        បង្កើតអ្នកប្រើប្រាស់
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany


                @canany(['បញ្ជីប្រភេទផលិតផល', 'បង្កើតប្រភេទផលិតផល'])
                    <li
                        class="submenu {{ request()->routeIs('categories.index', 'categories.create', 'brands.index', 'brands.create') || request()->is('categories/*/edit', 'brands/*/edit') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-collection fs-5"></i>
                            <span>ប្រភេទផលិតផល</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('បញ្ជីប្រភេទផលិតផល')
                                <li>
                                    <a href="{{ route('categories.index') }}"
                                        class="{{ request()->routeIs('categories.index') || request()->is('categories/*/edit') ? 'active' : '' }}">
                                        បញ្ជីប្រភេទផលិតផល
                                    </a>
                                </li>
                            @endcan
                            @can('បង្កើតប្រភេទផលិតផល')
                                <li>
                                    <a href="{{ route('categories.create') }}"
                                        class="{{ request()->routeIs('categories.create') ? 'active' : '' }}">
                                        បង្កើតប្រភេទផលិតផល
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany


                @canany(['បញ្ជីម៉ាកយីហោ', 'បង្កើតម៉ាកយីហោ'])
                    <li
                        class="submenu {{ request()->routeIs('brands.index', 'brands.create') || request()->is('categories/*/edit', 'brands/*/edit') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-app-indicator  fs-5"></i>
                            <span>ម៉ាកយីហោផលិតផល</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('បញ្ជីម៉ាកយីហោ')
                                <li>
                                    <a href="{{ route('brands.index') }}"
                                        class="{{ request()->routeIs('brands.index') || request()->is('brands/*/edit') ? 'active' : '' }}">
                                        បញ្ជីម៉ាកយីហោ
                                    </a>
                                </li>
                            @endcan

                            @can('បង្កើតម៉ាកយីហោ')
                                <li>
                                    <a href="{{ route('brands.create') }}"
                                        class="{{ request()->routeIs('brands.create') ? 'active' : '' }}">
                                        បង្កើតម៉ាកយីហោ
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['បញ្ជីផលិតផល', 'បង្កើតផលិតផល'])
                    <li
                        class="submenu {{ request()->routeIs('products.index') || request()->is('products/create') || request()->is('products/*/edit') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-journal-bookmark fs-5"></i>
                            <span>ផលិតផល</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('បញ្ជីផលិតផល')
                                <li>
                                    <a href="{{ route('products.index') }}"
                                        class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                                        បញ្ជីផលិតផល
                                    </a>
                                </li>
                            @endcan
                            @can('បង្កើតផលិតផល')
                                <li>
                                    <a href="{{ route('products.create') }}"
                                        class="{{ request()->is('products/create') ? 'active' : '' }}">
                                        បង្កើតផលិតផល
                                    </a>
                                </li>
                            @endcan


                        </ul>
                    </li>
                @endcanany


                @canany(['ផ្ទាំងលក់ផលិតផល'])
                    <li
                        class="submenu {{ request()->routeIs('pos.index') || request()->is('pos/*/edit') || request()->routeIs('pos.index') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-grid"></i>
                            <span>POS</span></span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('pos.index') }}"
                                    class="{{ request()->routeIs('pos.index') || request()->is('pos/*/edit') ? 'active' : '' }}">
                                    POS
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcanany


                @canany(['បញ្ជីអ្នកផ្គត់ផ្គង់', 'បង្កើតអ្នកផ្គត់ផ្គង់'])
                    <li class="submenu {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-truck fs-5"></i>
                            <span>អ្នកផ្គត់ផ្គង់</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('បញ្ជីអ្នកផ្គត់ផ្គង់')
                                <li>
                                    <a href="{{ route('suppliers.index') }}"
                                        class="{{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
                                        បញ្ជីអ្នកផ្គត់ផ្គង់
                                    </a>
                                </li>
                            @endcan

                            @can('បង្កើតអ្នកផ្គត់ផ្គង់')
                                <li>
                                    <a href="{{ route('suppliers.create') }}"
                                        class="{{ request()->routeIs('suppliers.create') ? 'active' : '' }}">
                                        បង្កើតអ្នកផ្គត់ផ្គង់
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany


                @canany(['បញ្ជីការទូទាត់ការបញ្ជាទិញ', 'បញ្ជីការបញ្ជាទិញ', 'បង្កើតការបញ្ជាទិញ'])
                    <li class="submenu {{ request()->routeIs('purchases.*', 'purchase_payments.index') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-bag fs-5"></i>
                            <span>ការបញ្ជារទិញ</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('បញ្ជីការបញ្ជាទិញ')
                                <li>
                                    <a href="{{ route('purchases.index') }}"
                                        class="{{ request()->routeIs('purchases.index') ? 'active' : '' }}">
                                        បញ្ជីការបញ្ជាទិញ
                                    </a>
                                </li>
                            @endcan

                            @can('បង្កើតការបញ្ជាទិញ')
                                <li>
                                    <a href="{{ route('purchases.create') }}"
                                        class="{{ request()->routeIs('purchases.create') ? 'active' : '' }}">
                                        បង្កើតការបញ្ជាទិញ
                                    </a>
                                </li>
                            @endcan
                            @can('បញ្ជីការទូទាត់ការបញ្ជាទិញ')
                                <li>
                                    <a href="{{ route('purchase_payments.index') }}"
                                        class="{{ request()->routeIs('purchase_payments.index') ? 'active' : '' }}">
                                        បញ្ជីការទូទាត់ការបញ្ជាទិញ
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany


                @canany(['បញ្ជីអតិថិជន', 'បង្កើតអតិថិជន'])
                    <li class="submenu {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-people fs-5"></i>
                            <span>អតិថិជន</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('បញ្ជីអតិថិជន')
                                <li>
                                    <a href="{{ route('customers.index') }}"
                                        class="{{ request()->routeIs('customers.index') ? 'active' : '' }}">
                                        បញ្ជីអតិថិជន
                                    </a>
                                </li>
                            @endcan
                            @can('បង្កើតអតិថិជន')
                                <li>
                                    <a href="{{ route('customers.create') }}"
                                        class="{{ request()->routeIs('customers.create') ? 'active' : '' }}">
                                        បង្កើតអតិថិជន
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['បញ្ជីការលក់', 'បង្កើតការលក់', 'បញ្ជីការទូទាត់ការលក់'])
                    <li class="submenu {{ request()->routeIs('sales.*', 'sale_payments.index') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-receipt fs-5"></i>
                            <span>ការលក់</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('បញ្ជីការលក់')
                                <li>
                                    <a href="{{ route('sales.index') }}"
                                        class="{{ request()->routeIs('sales.index') ? 'active' : '' }}">
                                        បញ្ជីការលក់
                                    </a>
                                </li>
                            @endcan

                            @can('បង្កើតការលក់')
                                <li>
                                    <a href="{{ route('sales.create') }}"
                                        class="{{ request()->routeIs('sales.create') ? 'active' : '' }}">
                                        បង្កើតការលក់
                                    </a>
                                </li>
                            @endcan
                            @can('បញ្ជីការទូទាត់ការលក់')
                                <li>
                                    <a href="{{ route('sale_payments.index') }}"
                                        class="{{ request()->routeIs('sale_payments.index') ? 'active' : '' }}">
                                        បញ្ជីការទូទាត់ការលក់
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['បញ្ជីបង្វែចូលទំនិញ', 'បង្កើតបង្វែចូលទំនិញ'])
                    <li class="submenu {{ request()->routeIs('sale-returns.*', 'sale-returns.index') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-receipt fs-5"></i>
                            <span>បង្វែចូលទំនិញ</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('')
                                <li>
                                    <a href="{{ route('sale-returns.index') }}"
                                        class="{{ request()->routeIs('sale-returns.index') ? 'active' : '' }}">
                                        បញ្ជីបង្វែចូលទំនិញ
                                    </a>
                                </li>
                            @endcan

                            @can('')
                                <li>
                                    <a href="{{ route('sale_returns.reference') }}"
                                        class="{{ request()->routeIs('sale_returns.reference') ? 'active' : '' }}">
                                        បង្កើតបង្វែចូលទំនិញ
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['បញ្ជីប្រភេទការចំណាយ', 'បង្កើតប្រភេទការចំណាយ', 'បញ្ជីការចំណាយ', 'បង្កើតការចំណាយ'])
                    <li
                        class="submenu {{ request()->routeIs('expenses.*', 'expense_categories.index') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-bag fs-5"></i>
                            <span>ចំណាយ</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('បញ្ជីប្រភេទការចំណាយ')
                                <li>
                                    <a href="{{ route('expense_categories.index') }}"
                                        class="{{ request()->routeIs('expense_categories.index') ? 'active' : '' }}">
                                        បញ្ជីប្រភេទចំណាយ
                                    </a>
                                </li>
                            @endcan
                            @can('បង្កើតប្រភេទការចំណាយ')
                                <li>
                                    <a href="{{ route('expense_categories.create') }}"
                                        class="{{ request()->routeIs('expense_categories.create') ? 'active' : '' }}">
                                        បង្កើតប្រភេទការចំណាយ
                                    </a>
                                </li>
                            @endcan

                            @canany(['បញ្ជីប្រភេទការចំណាយ', 'បង្កើតប្រភេទការចំណាយ'])
                                <hr>
                            @endcanany
                            @can('បញ្ជីការចំណាយ')
                                <li>
                                    <a href="{{ route('expenses.index') }}"
                                        class="{{ request()->routeIs('expenses.index') ? 'active' : '' }}">
                                        បញ្ជីការចំណាយ
                                    </a>
                                </li>
                            @endcan
                            @can('បង្កើតការចំណាយ')
                                <li>
                                    <a href="{{ route('expenses.create') }}"
                                        class="{{ request()->routeIs('expenses.create') ? 'active' : '' }}">
                                        បង្កើតការចំណាយ
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany


                @canany(['របាយការណ៍ការលក់ទំនិញ', 'របាយការណ៍ការទិញ', 'របាយការណ៍ប្រាក់ចំណេញ'])

                    <li
                        class="submenu {{ request()->routeIs('admin.reports.sales', 'admin.purchase.report', 'profit.loss.report') ? 'active' : '' }}">
                        <a href="#">
                            <i class="bi bi-graph-up fs-5"></i>
                            <span>របាយការណ៍</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @can('របាយការណ៍ការលក់ទំនិញ')
                                <li>
                                    <a href="{{ route('admin.reports.sales') }}"
                                        class="{{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
                                        របាយការណ៍ការលក់
                                    </a>
                                </li>
                            @endcan


                            @can('របាយការណ៍ផលិតផល')
                                <li>
                                    <a href="{{ route('admin.reports.product') }}"
                                        class="{{ request()->routeIs('admin.reports.product') ? 'active' : '' }}">
                                        របាយការណ៍ផលិតផល
                                    </a>
                                </li>
                            @endcan



                            @can('របាយការណ៍ការទិញ')
                                <li>
                                    <a href="{{ route('admin.purchase.report') }}"
                                        class="{{ request()->routeIs('admin.purchase.report') ? 'active' : '' }}">
                                        របាយការណ៍ការទិញ
                                    </a>
                                </li>
                            @endcan

                            @can('របាយការណ៍ប្រាក់ចំណេញ និងខាត')
                                <li>
                                    <a href="{{ route('profit.loss.report') }}"
                                        class="{{ request()->routeIs('profit.loss.report') ? 'active' : '' }}">
                                        របាយការណ៍ប្រាក់ចំណេញ និងខាត
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
            </ul>
        </div>
    </div>
</div>
