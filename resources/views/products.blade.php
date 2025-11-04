<!-- product blade -->
@extends('layouts.header')

@section('content')

<div class="search-overlay" id="searchOverlay"></div>

<!-- Search Container -->
<div class="search-container" id="searchContainer">
  <form class="search-form" id="searchForm">
    <div class="search-input-wrapper"> 
      <input 
        type="text" 
        class="search-input" 
        id="searchInput" 
        placeholder="Search for products..."
        autocomplete="off"
      >
      <div class="search-results" id="searchResults"></div>
    </div>
    <button type="submit" class="search-btn">Search</button>
    <button type="button" class="close-search" id="closeSearch">
      <i class="bi bi-x"></i>
    </button>
  </form>
</div>

<!-- Using Bootstrap classes for top controls -->
<div class="top-controls mt-1 d-flex justify-content-between align-items-center p-3">
  <div class="dropdown">
  <button class="category-dropdown" type="button" id="categoryDropdown">
    <span id="selectedCategory">Select Customer</span>
    <i class="bi bi-chevron-down"></i>
  </button>
  <ul class="dropdown-menu" id="categoryDropdownMenu">
    <li class="dropdown-search-wrapper">
      <input type="text" class="dropdown-search-input" id="customerSearchInput" placeholder="Type to search customer..." autocomplete="off">
    </li>
    <li class="dropdown-empty-state" id="dropdownEmptyState">
      <div class="empty-state-content">
        <i class="bi bi-search"></i>
        <p>Start typing to search customers...</p>
      </div>
    </li>
    <li class="dropdown-no-results hidden" id="dropdownNoResults">
      <div class="empty-state-content">
        <i class="bi bi-exclamation-circle"></i>
        <p>No customers found</p>
      </div>
    </li>
    <li class="customer-list-wrapper">
      <a class="dropdown-item category-filter hidden" href="#" data-customer-id="">
        -- Clear Selection --
      </a>
      @foreach($customers as $customer)
        @php
        $fullName = $customer->name;
        $parts = explode(' ', $fullName);
        $lastName = array_pop($parts);
        $masked = str_repeat('*', strlen(implode(' ', $parts))) . ' ' . $lastName;
        
        $serial = $customer->serial->serial_number ?? '';
        $masked_serial = str_repeat('*', max(0, strlen($serial) - 5)) . substr($serial, -5);
        
        $number = $customer->number;
        $masked_number = str_repeat('*', max(0, strlen($number) - 5)) . substr($number, -5);
        @endphp
        <a class="dropdown-item category-filter customer-option hidden" href="#" 
          data-customer-id="{{ $customer->id }}"
          data-name="{{ $masked }}"
          data-serial="{{ $masked_serial }}"
          data-number="{{ $masked_number }}"
          data-full-name="{{ $fullName }}"
          data-search="{{ strtolower($masked . ' ' . $masked_serial . ' ' . $masked_number) }}">
          <div class="customer-info">
            <div class="customer-name">{{ $masked }}</div>
            <div class="customer-details">
              <span class="customer-serial">Serial: {{ $masked_serial }}</span>
              <span class="customer-number">Number: {{ $masked_number }}</span>
            </div>
          </div>
        </a>
      @endforeach
    </li>
  </ul>
</div>
  <!-- Using Bootstrap flex utilities -->
  <div class="d-flex gap-3">
    <div class="nav-icon-container">
    <i class="bi bi-search text-secondary fs-5 cursor-pointer"></i>
    </div>
    <a href="{{url('/cart')}}" class="nav-item {{ request()->is('cart') ? 'active' : '' }}">
        <div class="nav-icon-container">
            <i class="bi bi-cart"></i>
            <div class="cart-badge" id="cartBadge">0</div>
        </div>
    </a>
  </div>
</div>

<div class="content-area container-fluid" id="contentWrapper">
  <div class="row g-2">
    @forelse($products as $index => $product)
        <div class="col-6">
          <div class="product-card-container bg-white rounded-3 shadow-sm border" id="container-{{ $product->id }}">
            <div class="product-card p-3 text-center h-100">
              <div class="product-image-container d-flex justify-content-center align-items-center mb-3 rounded-2">
                <img src="{{ $product->item_image }}" alt="{{ $product->item }}" id="productImage">
              </div>
              
              <div class="product-info d-flex flex-column h-100">
                <div class="product-name mb-2" id="productName">{{ $product->item }}</div>
                
                <div class="price-add-container d-flex justify-content-between align-items-center mt-auto">
                  <div class="product-price fw-bold text-primary flex-grow-1 text-start">₱ {{ number_format($product->price, 2) }}</div>
                  <div class="add-to-cart">
                    @if(stripos($product->product_name, 'stove') !== false)
                      <button class="add-btn btn btn-primary rounded-circle p-0 d-flex justify-content-center align-items-center" 
                              data-id="{{ $product->id }}"
                              data-container="container-{{ $product->id }}" 
                              data-price="{{ $product->price }}" 
                              data-name="{{ $product->product_name }}" 
                              data-image="{{ $product->item_image }}">
                        <i class="bi bi-plus"></i>
                      </button>
                    @else
                      <button class="add-btn btn btn-primary rounded-circle p-0 d-flex justify-content-center align-items-center" 
                            data-id="{{ $product->id }}"
                            data-price="{{ $product->price }}" 
                            data-name="{{ $product->item }}" 
                            data-image="{{ $product->item_image }}">
                      <i class="bi bi-plus"></i>
                    </button>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
      <!-- Bootstrap utilities for empty state -->
      <div class="col-12">
        <div class="text-center py-5 text-muted">
          <i class="bi bi-box display-1 mb-3 d-block text-light"></i>
          <h3 class="fs-5 mb-2">No Products Available</h3>
          <p class="fs-6">There are currently no products to display.</p>
        </div>
      </div>
    @endforelse
  </div>
</div>
@endsection

@section('css')

<style>
  /* Fixed top controls */
.top-controls {
  background: #fff;
  margin-top: 0px !important;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  outline: 0.2px solid #e1e1e1ff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  padding: 15px 20px;
}

/* Content area adjustments */
.content-area {
  padding: 15px !important;
  text-align: left !important;
  padding-bottom: 120px !important;
}

/* Toast Notification */
.toast {
  position: fixed;
  top: 80px;
  right: 20px;
  padding: 12px 20px;
  border-radius: 8px;
  z-index: 9999;
  font-weight: 500;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  transform: translateX(100%);
  transition: transform 0.3s ease;
  max-width: 300px;
  font-size: 14px;
}

.toast.success {
  background: #28a745;
  color: white;
}

.toast.error {
  background: #dc3545;
  color: white;
}

.toast.show {
  transform: translateX(0);
}

/* Customer Dropdown Button */
.category-dropdown {
  background: #fff;
  border: 2px solid #e9ecef;
  padding: 10px 16px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  color: #333;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s ease;
  min-width: 250px !important;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.category-dropdown:hover {
  border-color: #4A90E2;
  box-shadow: 0 3px 8px rgba(74, 144, 226, 0.15);
}

.category-dropdown:focus {
  outline: none;
  border-color: #4A90E2;
  box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
}

#selectedCategory {
  flex: 1;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: #666;
}

.category-dropdown.has-selection #selectedCategory {
  color: #333;
  font-weight: 600;
}

.category-dropdown i {
  font-size: 12px;
  color: #999;
  transition: transform 0.2s ease;
}

.category-dropdown.active i {
  transform: rotate(180deg);
}

/* Dropdown Empty States */
.dropdown-empty-state,
.dropdown-no-results {
  padding: 2rem 1.5rem;
  text-align: center;
  pointer-events: none;
  user-select: none;
}

.empty-state-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.75rem;
}

.empty-state-content i {
  font-size: 2.5rem;
  opacity: 0.4;
  color: #6c757d;
}

.dropdown-empty-state .empty-state-content i {
  color: #0d6efd;
  opacity: 0.5;
}

.dropdown-no-results .empty-state-content i {
  color: #dc3545;
  opacity: 0.5;
}

.empty-state-content p {
  margin: 0;
  font-size: 0.9rem;
  color: #6c757d;
  font-weight: 500;
}

/* Hidden state */
.hidden {
  display: none !important;
}

/* Dropdown Container */
.dropdown {
  position: relative;
}

/* Dropdown Menu */
.dropdown-menu {
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  z-index: 1050;
  display: none;
  min-width: 320px;
  padding: 8px 0;
  margin: 0;
  font-size: 14px;
  list-style: none;
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 10px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  max-height: 400px;
  overflow-y: auto;
}

.dropdown-menu.show {
  display: block;
  animation: dropdownSlideIn 0.2s ease-out;
}

@keyframes dropdownSlideIn {
  from {
    opacity: 0;
    transform: translateY(-8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Search Input in Dropdown */
.dropdown-search-wrapper {
  padding: 8px 12px;
  background: #f8f9fa;
  border-bottom: 1px solid #e9ecef;
  margin-bottom: 4px;
  position: sticky;
  top: 0;
  z-index: 1;
}

.dropdown-search-input {
  width: 100%;
  padding: 10px 12px 10px 36px;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  font-size: 13px;
  outline: none;
  transition: all 0.2s ease;
  background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23999' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") no-repeat 12px center;
}

.dropdown-search-input:focus {
  border-color: #4A90E2;
  box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
}

.dropdown-search-input::placeholder {
  color: #999;
}

/* Dropdown Items */
.dropdown-item {
  display: block;
  width: 100%;
  padding: 10px 16px;
  color: #333;
  text-decoration: none;
  background: transparent;
  border: 0;
  transition: all 0.15s ease;
  cursor: pointer;
}

.dropdown-item:hover {
  background: #f8f9fa;
}

/* Default Select Customer Option */
.dropdown-item[data-customer-id=""] {
  font-weight: 600;
  color: #666;
  border-bottom: 1px solid #e9ecef;
  padding: 12px 16px;
}

.dropdown-item[data-customer-id=""]:hover {
  background: #f0f0f0;
}

/* Customer Option Items */
.customer-option {
  padding: 12px 16px !important;
  border-left: 3px solid transparent;
}

.customer-option:hover {
  background: linear-gradient(90deg, #f0f7ff 0%, #ffffff 100%);
  border-left-color: #4A90E2;
}

.customer-option.active {
  background: #e8f4fd;
  border-left-color: #4A90E2;
}

.customer-option.active .customer-name {
  color: #4A90E2;
  font-weight: 700;
}

/* Hide filtered items */
.dropdown-item.hidden {
  display: none !important;
}

/* Customer Info Layout */
.customer-info {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.customer-name {
  font-size: 14px !important;
  font-weight: 600;
  color: #333;
  line-height: 1.3;
}

.customer-details {
  display: flex;
  gap: 12px;
  font-size: 12px;
  color: #666;
}

.customer-serial,
.customer-number {
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.customer-serial::before {
  content: "📋";
  font-size: 10px;
}

.customer-number::before {
  content: "📱";
  font-size: 10px;
}

.customer-option:hover .customer-name {
  color: #4A90E2;
}

.customer-option:hover .customer-details {
  color: #4A90E2;
}

/* Product grid and cards */
.product-card-container {
  transition: all 0.3s ease;
}

.product-card {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.product-image-container {
  height: 150px;
  background: #f8f9fa;
  display: flex;
  align-items: center;
  justify-content: center;
}

.product-image-container img {
  max-height: 100%;
  max-width: 100%;
  object-fit: contain;
}

.product-info {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.product-name {
  font-size: 14px;
  font-weight: 500;
  color: #333;
  margin-bottom: 8px;
  line-height: 1.3;
  min-height: 36px;
}

.price-add-container {
  margin-top: auto;
}

.product-price {
  font-size: 16px;
  font-weight: 700;
  color: #4A90E2;
}

.add-btn {
  width: 32px;
  height: 32px;
  background: #4A90E2;
  color: white;
  border: none;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 14px;
}

.add-btn:hover {
  background: #186ed1;
  transform: scale(1.05);
}

.add-btn.has-quantity {
  background: #ca1f1f;
}

.add-btn.has-quantity:hover {
  background: #ca1f1f;
}

.quantity-badge {
  font-size: 12px;
  font-weight: 700;
  color: #fff;
}

/* Filter animations */
.product-card-container.filtered-out { 
  display: none !important; 
}

.product-card-container.filtered-in {
  display: block;
  animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
  from { 
    opacity: 0; 
    transform: translateY(20px); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0); 
  }
}

.filter-empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #666;
}

.filter-empty-state i {
  font-size: 48px;
  margin-bottom: 16px;
  display: block;
  color: #ccc;
}

.filter-empty-state h3 {
  font-size: 18px;
  margin-bottom: 8px;
  font-weight: 600;
}

.clear-filter-btn {
  background: #4A90E2;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s ease;
}

.clear-filter-btn:hover { 
  background: #186ed1; 
}

/* Search functionality */
.search-container {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: #fff;
  padding: 15px 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  z-index: 1500;
  transform: translateY(-100%);
  transition: transform 0.3s ease-in-out;
  border-bottom: 1px solid #e1e1e1;
}

.search-container.active { 
  transform: translateY(0); 
}

.search-form {
  display: flex;
  align-items: center;
  gap: 15px;
}

.search-input-wrapper {
  flex: 1;
  position: relative;
}

.search-input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e1e1e1;
  border-radius: 8px;
  font-size: 16px;
  outline: none;
  transition: border-color 0.2s ease;
  background: #f8f9fa;
}

.search-input:focus {
  border-color: #4A90E2;
  background: #fff;
}

.search-input::placeholder { 
  color: #999; 
}

.search-btn {
  background: #4A90E2;
  color: #fff;
  border: none;
  padding: 12px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s ease;
  white-space: nowrap;
}

.search-btn:hover { 
  background: #186ed1; 
}

.close-search {
  background: none;
  border: none;
  font-size: 18px;
  color: #666;
  cursor: pointer;
  padding: 8px;
  border-radius: 50%;
  transition: background 0.2s ease;
}

.close-search:hover { 
  background: #f5f5f5; 
}

.search-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.3);
  z-index: 1400;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.search-overlay.active {
  opacity: 1;
  visibility: visible;
}

.search-results {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: #fff;
  border: 1px solid #e1e1e1;
  border-top: none;
  border-radius: 0 0 8px 8px;
  max-height: 300px;
  overflow-y: auto;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  z-index: 10;
}

.search-result-item {
  padding: 12px 16px;
  border-bottom: 1px solid #f0f0f0;
  cursor: pointer;
  transition: background 0.2s ease;
  display: flex;
  align-items: center;
  gap: 12px;
}

.search-result-item:hover { 
  background: #f8f9fa; 
}

.search-result-item:last-child { 
  border-bottom: none; 
}

.search-result-image {
  width: 40px;
  height: 40px;
  object-fit: contain;
  border-radius: 6px;
  background: #f5f5f5;
}

.search-result-info { 
  flex: 1; 
}

.search-result-name {
  font-size: 14px;
  font-weight: 500;
  color: #333;
  margin-bottom: 2px;
}

.search-result-price {
  font-size: 13px;
  color: #4A90E2;
  font-weight: 600;
}

.no-results {
  padding: 20px;
  text-align: center;
  color: #666;
  font-style: italic;
}

/* Color selection modal */
.color-selection-expansion {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 12px;
  padding: 0;
  width: 90%;
  max-width: 350px;
  max-height: 80vh;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  z-index: 2000;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.color-selection-expansion.active {
  opacity: 1;
  visibility: visible;
  padding: 20px;
}

.expansion-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1999;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.expansion-overlay.active {
  opacity: 1;
  visibility: visible;
}

.expansion-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 1px solid #e9ecef;
}

.expansion-title {
  font-size: 16px;
  font-weight: 600;
  color: #333;
  margin: 0;
}

.close-expansion {
  background: none;
  border: none;
  color: #666;
  font-size: 18px;
  cursor: pointer;
  padding: 4px;
  border-radius: 50%;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s ease;
}

.close-expansion:hover {
  background: #f5f5f5;
  color: #333;
}

.color-options { 
  margin-bottom: 20px; 
}

.color-option-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
  border-bottom: 1px solid #f0f0f0;
}

.color-option-row:last-child { 
  border-bottom: none; 
}

.color-label {
  font-size: 14px;
  font-weight: 500;
  color: #333;
  text-transform: capitalize;
  min-width: 60px;
}

.color-input-wrapper {
  display: flex;
  align-items: center;
  gap: 8px;
}

.color-quantity-input {
  width: 60px;
  padding: 6px 8px;
  border: 1px solid #ddd;
  border-radius: 6px;
  text-align: center;
  font-size: 14px;
  font-weight: 500;
  outline: none;
  transition: border-color 0.2s ease;
}

.color-quantity-input:focus { 
  border-color: #4A90E2; 
}

.color-quantity-input::-webkit-outer-spin-button,
.color-quantity-input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.color-quantity-input[type=number] { 
  -moz-appearance: textfield; 
}

.expansion-total {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 0;
  border-top: 1px solid #e9ecef;
  margin-bottom: 15px;
}

.expansion-total-label {
  font-size: 14px;
  font-weight: 500;
  color: #666;
}

.expansion-total-price {
  font-size: 16px;
  font-weight: 700;
  color: #4A90E2;
}

.add-to-cart-expansion {
  background: #4A90E2;
  color: #fff;
  border: none;
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  width: 100%;
}

.add-to-cart-expansion:hover { 
  background: #186ed1; 
}

.add-to-cart-expansion:disabled {
  background: #ccc;
  cursor: not-allowed;
}

/* Quantity modal */
.quantity-modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 12px;
  padding: 0;
  width: 90%;
  max-width: 400px;
  max-height: 80vh;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  z-index: 2000;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.quantity-modal.active {
  opacity: 1;
  visibility: visible;
  padding: 20px;
}

.product-info-modal {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 15px 0;
  border-bottom: 1px solid #f0f0f0;
  margin-bottom: 20px;
}

.modal-product-image {
  width: 60px;
  height: 60px;
  object-fit: contain;
  border-radius: 8px;
  background: #f5f5f5;
  padding: 8px;
}

.modal-product-details {
  flex: 1;
}

.modal-product-name {
  font-size: 16px;
  font-weight: 600;
  color: #333;
  margin-bottom: 4px;
  line-height: 1.3;
}

.modal-product-price {
  font-size: 18px;
  font-weight: 700;
  color: #4A90E2;
}

.quantity-selection {
  margin-bottom: 20px;
}

.quantity-input-section {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.quantity-label {
  font-size: 14px;
  font-weight: 600;
  color: #333;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 12px;
  justify-content: center;
}

.quantity-btn {
  background: #f8f9fa;
  border: 2px solid #e9ecef;
  color: #495057;
  width: 40px;
  height: 40px;
  border-radius: 8px;
  font-size: 18px;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  user-select: none;
}

.quantity-btn:hover {
  background: #e9ecef;
  border-color: #dee2e6;
  transform: translateY(-1px);
}

.quantity-btn:active {
  transform: translateY(0) scale(0.95);
}

.quantity-btn.minus-btn {
  color: #dc3545;
  border-color: #f5c6cb;
}

.quantity-btn.minus-btn:hover {
  background: #f8d7da;
  border-color: #f1aeb5;
}

.quantity-btn.plus-btn {
  color: #28a745;
  border-color: #c3e6cb;
}

.quantity-btn.plus-btn:hover {
  background: #d4edda;
  border-color: #b8dabd;
}

.quantity-input {
  width: 80px;
  height: 40px;
  text-align: center;
  font-size: 16px;
  font-weight: 600;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  outline: none;
  transition: border-color 0.2s ease;
}

.quantity-input:focus {
  border-color: #4A90E2;
  box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.quantity-input[type=number] {
  -moz-appearance: textfield;
}

.modal-actions {
  display: flex;
  gap: 12px;
  margin-top: 20px;
  padding-top: 15px;
  border-top: 1px solid #e9ecef;
}

.modal-action-btn {
  flex: 1;
  padding: 12px 16px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  text-align: center;
}

.cancel-btn {
  background: #f8f9fa;
  color: #6c757d;
  border: 1px solid #e9ecef;
}

.cancel-btn:hover {
  background: #e9ecef;
  color: #495057;
  transform: translateY(-1px);
}

.confirm-btn {
  background: #4A90E2;
  color: #fff;
  box-shadow: 0 2px 4px rgba(74, 144, 226, 0.3);
}

.confirm-btn:hover {
  background: #186ed1;
  transform: translateY(-1px);
  box-shadow: 0 3px 6px rgba(74, 144, 226, 0.4);
}

/* Cart summary */
.cart-summary-wrapper {
  position: fixed;
  bottom: 100px;
  left: 15px;
  right: 15px;
  z-index: 1000;
}

.cart-summary-btn {
  width: 100%;
  background: linear-gradient(135deg, #4A90E2 0%, #357abd 100%);
  color: #fff;
  border: none;
  padding: 16px 20px;
  font-size: 15px;
  font-weight: 600;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(74, 144, 226, 0.4);
  cursor: pointer;
  text-align: center;
  transition: all 0.2s ease;
}

.cart-summary-btn:active {
  transform: scale(0.98);
  box-shadow: 0 2px 8px rgba(74, 144, 226, 0.6);
}

.cart-summary-btn i {
  font-size: 18px;
  margin-right: 6px;
}

/* Camera modal styles */
.camera-modal-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  z-index: 2000;
  align-items: center;
  justify-content: center;
}

.camera-modal-content {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 400px;
  max-height: 80vh;
  overflow: hidden;
}

.camera-header {
  padding: 20px;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.camera-header h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  color: #333;
}

.camera-close {
  background: none;
  border: none;
  font-size: 18px;
  cursor: pointer;
  padding: 4px;
  border-radius: 50%;
  color: #666;
}

.camera-container {
  position: relative;
  background: #000;
}

#cameraVideo {
  width: 100%;
  height: 300px;
  object-fit: cover;
}

.camera-controls {
  padding: 20px;
  text-align: center;
}

.camera-status {
  font-size: 14px;
  color: #666;
}

/* Mobile responsive */
@media (max-width: 480px) {
  .top-controls {
    padding: 12px 15px;
  }
  
  .content-area {
    margin-top: 0px !important;
    padding: 12px !important;
  }
  
  .category-dropdown {
    min-width: 160px;
    padding: 8px 12px;
    font-size: 13px;
  }
  
  .dropdown-menu {
    min-width: 280px;
  }
  
  .customer-name {
    font-size: 13px;
  }
  
  .customer-details {
    font-size: 11px;
    gap: 8px;
  }
  
  .customer-option {
    padding: 10px 12px !important;
  }
  
  .product-name {
    font-size: 12px;
    min-height: 30px;
  }
  
  .product-price { 
    font-size: 15px; 
  }
  
  .add-btn {
    width: 28px;
    height: 28px;
  }
  
  .add-btn i { 
    font-size: 12px; 
  }
  
  .color-selection-expansion,
  .quantity-modal {
    width: 95%;
    max-height: 75vh;
  }
  
  .color-selection-expansion.active,
  .quantity-modal.active { 
    padding: 15px; 
  }
  
  .color-quantity-input {
    width: 50px;
    padding: 5px 6px;
    font-size: 13px;
  }
  
  .expansion-title { 
    font-size: 15px; 
  }
  
  .color-label {
    font-size: 13px;
    min-width: 55px;
  }
  
  .search-container { 
    padding: 12px 15px; 
  }
  
  .search-input {
    padding: 10px 14px;
    font-size: 14px;
  }
  
  .search-btn {
    padding: 10px 16px;
    font-size: 13px;
  }
  
  .modal-product-image {
    width: 50px;
    height: 50px;
  }
  
  .modal-product-name {
    font-size: 14px;
  }
  
  .modal-product-price {
    font-size: 16px;
  }
  
  .quantity-btn {
    width: 36px;
    height: 36px;
    font-size: 16px;
  }
  
  .quantity-input {
    width: 70px;
    height: 36px;
    font-size: 14px;
  }
  
  .modal-action-btn {
    padding: 10px 14px;
    font-size: 13px;
  }
  
  .quantity-badge {
    font-size: 12px;
  }
}

@media (max-width: 375px) {
  .top-controls {
    padding: 10px 12px;
  }
  
  .category-dropdown {
    min-width: 140px;
    padding: 7px 10px;
    font-size: 12px;
  }
  
  .dropdown-menu {
    min-width: 260px;
  }
}
</style>
@endsection

@section('js')

<script>
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
  // ===== SEARCH FUNCTIONALITY =====
  const searchContainer = document.getElementById('searchContainer');
  const searchOverlay = document.getElementById('searchOverlay');
  const searchInput = document.getElementById('searchInput');
  const searchResults = document.getElementById('searchResults');
  const searchForm = document.getElementById('searchForm');
  const closeSearchBtn = document.getElementById('closeSearch');
  const searchIcon = document.querySelector('.nav-icon-container .bi-search');

  const productCards = document.querySelectorAll('.product-card-container');
  const contentWrapper = document.getElementById('contentWrapper');

  let allProducts = [];

  // Initialize products array from DOM
  productCards.forEach(card => {
    const nameElement = card.querySelector('.product-name');
    const priceElement = card.querySelector('.product-price');
    const imageElement = card.querySelector('img');
    
    if (nameElement && priceElement) {
      allProducts.push({
        name: nameElement.textContent.trim(),
        price: priceElement.textContent.trim(),
        image: imageElement ? imageElement.src : '',
        element: card
      });
    }
  });

  // Search functions
  function showSearch() {
    searchContainer.classList.add('active');
    searchOverlay.classList.add('active');
    searchInput.focus();
    document.body.style.overflow = 'hidden';
  }

  function hideSearch() {
    searchContainer.classList.remove('active');
    searchOverlay.classList.remove('active');
    searchInput.value = '';
    searchResults.innerHTML = '';
    document.body.style.overflow = '';
  }

  function performSearch(query) {
    if (!query.trim()) {
      searchResults.innerHTML = '';
      return;
    }

    const filtered = allProducts.filter(product => 
      product.name.toLowerCase().includes(query.toLowerCase())
    );

    if (filtered.length === 0) {
      searchResults.innerHTML = '<div class="no-results">No products found</div>';
      return;
    }

    const resultsHTML = filtered.map(product => `
      <div class="search-result-item" data-product-name="${product.name}">
        <img src="${product.image}" alt="${product.name}" class="search-result-image" onerror="this.src='${window.location.origin}/images/stovewcyllinder.jpg'">
        <div class="search-result-info">
          <div class="search-result-name">${product.name}</div>
          <div class="search-result-price">${product.price}</div>
        </div>
      </div>
    `).join('');

    searchResults.innerHTML = resultsHTML;
  }

  // Search event listeners
  if (searchIcon) {
    searchIcon.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      showSearch();
    });
  }

  if (closeSearchBtn) closeSearchBtn.addEventListener('click', (e) => { e.preventDefault(); hideSearch(); });
  if (searchOverlay) searchOverlay.addEventListener('click', (e) => { e.preventDefault(); hideSearch(); });
  if (searchInput) searchInput.addEventListener('input', (e) => performSearch(e.target.value));
  
  if (searchForm) {
    searchForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const query = searchInput.value.trim();
      if (query) {
        filterProducts(query);
        hideSearch();
      }
    });
  }

  // Handle search result clicks
  document.addEventListener('click', (e) => {
    if (e.target.closest('.search-result-item')) {
      const resultItem = e.target.closest('.search-result-item');
      const productName = resultItem.dataset.productName;
      filterProducts(productName);
      hideSearch();
    }
  });

  // ===== CUSTOMER DROPDOWN FUNCTIONALITY =====
  const categoryDropdown = document.getElementById('categoryDropdown');
  const categoryDropdownMenu = document.getElementById('categoryDropdownMenu');
  const selectedCategory = document.getElementById('selectedCategory');
  const categoryFilters = document.querySelectorAll('.category-filter');
  const customerSearchInput = document.getElementById('customerSearchInput');
  const emptyState = document.getElementById('dropdownEmptyState');
  const noResults = document.getElementById('dropdownNoResults');
  const customerOptions = document.querySelectorAll('.customer-option');
  const clearSelection = document.querySelector('.category-filter[data-customer-id=""]');

  let selectedCustomerId = '';

  // Initialize: show empty state
  if (emptyState) emptyState.classList.remove('hidden');
  if (noResults) noResults.classList.add('hidden');
  customerOptions.forEach(option => option.classList.add('hidden'));
  if (clearSelection) clearSelection.classList.add('hidden');

  // Toggle dropdown
  if (categoryDropdown) {
    categoryDropdown.addEventListener('click', (e) => {
      e.stopPropagation();
      categoryDropdownMenu.classList.toggle('show');
      
      if (categoryDropdownMenu.classList.contains('show') && customerSearchInput) {
        customerSearchInput.value = '';
        customerSearchInput.focus();
        if (emptyState) emptyState.classList.remove('hidden');
        if (noResults) noResults.classList.add('hidden');
        customerOptions.forEach(option => option.classList.add('hidden'));
        if (clearSelection) clearSelection.classList.add('hidden');
      }
    });
  }

  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown')) {
      categoryDropdownMenu.classList.remove('show');
    }
  });

  // Customer search functionality
  if (customerSearchInput) {
    customerSearchInput.addEventListener('input', (e) => {
      const searchTerm = e.target.value.toLowerCase().trim();
      
      if (searchTerm === '') {
        if (emptyState) emptyState.classList.remove('hidden');
        if (noResults) noResults.classList.add('hidden');
        customerOptions.forEach(option => option.classList.add('hidden'));
        if (clearSelection) clearSelection.classList.add('hidden');
        return;
      }
      
      if (emptyState) emptyState.classList.add('hidden');
      
      let hasResults = false;
      
      categoryFilters.forEach(filter => {
        if (filter.dataset.customerId === '') return;
        
        const searchData = filter.getAttribute('data-search') || '';
        const name = (filter.getAttribute('data-name') || '').toLowerCase();
        const serial = (filter.getAttribute('data-serial') || '').toLowerCase();
        const number = (filter.getAttribute('data-number') || '').toLowerCase();
        
        if (searchData.includes(searchTerm) ||
            name.includes(searchTerm) ||
            serial.includes(searchTerm) ||
            number.includes(searchTerm)) {
          filter.classList.remove('hidden');
          hasResults = true;
        } else {
          filter.classList.add('hidden');
        }
      });
      
      if (hasResults && clearSelection) {
        clearSelection.classList.remove('hidden');
      } else if (clearSelection) {
        clearSelection.classList.add('hidden');
      }
      
      if (!hasResults) {
        if (noResults) noResults.classList.remove('hidden');
      } else {
        if (noResults) noResults.classList.add('hidden');
      }
    });
    
    customerSearchInput.addEventListener('click', (e) => e.stopPropagation());
  }

  // Handle customer selection
  categoryFilters.forEach(filter => {
    filter.addEventListener('click', (e) => {
      e.preventDefault();
      const customerId = filter.dataset.customerId;
      const customerName = filter.dataset.name || '-- Select Customer --';
      const customerFullName = filter.dataset.fullName || '';
      const customerSerial = filter.dataset.serial || '';
      const customerNumber = filter.dataset.number || '';
      
      selectedCategory.textContent = customerName;
      
      categoryFilters.forEach(f => f.classList.remove('active'));
      filter.classList.add('active');
      
      selectedCustomerId = customerId;
      
      if (customerId) {
        localStorage.setItem('selectedCustomerId', customerId);
        localStorage.setItem('selectedCustomerName', customerName);
        localStorage.setItem('selectedCustomerFullName', customerFullName);
        localStorage.setItem('selectedCustomerSerial', customerSerial);
        localStorage.setItem('selectedCustomerNumber', customerNumber);
        
        console.log('Customer saved to localStorage:', {
          id: customerId,
          name: customerName,
          serial: customerSerial,
          number: customerNumber
        });
      } else {
        localStorage.removeItem('selectedCustomerId');
        localStorage.removeItem('selectedCustomerName');
        localStorage.removeItem('selectedCustomerFullName');
        localStorage.removeItem('selectedCustomerSerial');
        localStorage.removeItem('selectedCustomerNumber');
      }
      
      categoryDropdownMenu.classList.remove('show');
      
      if (customerSearchInput) {
        customerSearchInput.value = '';
        if (emptyState) emptyState.classList.remove('hidden');
        if (noResults) noResults.classList.add('hidden');
        customerOptions.forEach(option => option.classList.add('hidden'));
        if (clearSelection) clearSelection.classList.add('hidden');
      }
      
      console.log('Selected customer ID:', customerId);
    });
  });

  // ===== FILTERING FUNCTIONS =====
  function filterProducts(searchQuery) {
    let visibleCount = 0;
    
    productCards.forEach(card => {
      const productName = card.querySelector('.product-name').textContent.toLowerCase();
      const matchesSearch = productName.includes(searchQuery.toLowerCase());
      
      if (matchesSearch) {
        card.classList.remove('filtered-out');
        card.classList.add('filtered-in');
        visibleCount++;
      } else {
        card.classList.add('filtered-out');
        card.classList.remove('filtered-in');
      }
    });

    toggleEmptyState(visibleCount === 0);
  }

  function toggleEmptyState(show) {
    let emptyState = document.querySelector('.filter-empty-state');
    
    if (show && !emptyState) {
      const emptyHTML = `
        <div class="col-12">
          <div class="filter-empty-state">
            <i class="bi bi-search"></i>
            <h3>No products found</h3>
            <p>Try adjusting your search</p>
            <button class="clear-filter-btn" onclick="clearSearchFilter()">Clear Search</button>
          </div>
        </div>
      `;
      contentWrapper.querySelector('.row').insertAdjacentHTML('beforeend', emptyHTML);
    } else if (!show && emptyState) {
      emptyState.closest('.col-12').remove();
    }
  }

  window.clearSearchFilter = function() {
    productCards.forEach(card => {
      card.classList.remove('filtered-out');
      card.classList.add('filtered-in');
    });
    
    const emptyState = document.querySelector('.filter-empty-state');
    if (emptyState) {
      emptyState.closest('.col-12').remove();
    }
  };

  window.getSelectedCustomerId = function() {
    return selectedCustomerId;
  };

  // ===== CART FUNCTIONALITY =====
  const addButtons = document.querySelectorAll('.add-btn');
    
  let expansionOverlay = document.getElementById('expansionOverlay');
  if (!expansionOverlay) {
    expansionOverlay = document.createElement('div');
    expansionOverlay.className = 'expansion-overlay';
    expansionOverlay.id = 'expansionOverlay';
    document.body.appendChild(expansionOverlay);
  }

  let cart = {
    items: 0,
    amount: 0,
    products: []
  };

  const buttonQuantities = new Map();
  const buttonToProduct = new Map();
  const stoveColorQuantities = new Map();

  let currentExpansion = null;
  const availableColors = ['yellow', 'blue', 'red', 'white', 'choco', 'green'];

  // Customer selection from QR scan
  function selectCustomerInDropdown(customerId) {
    const customerOption = document.querySelector(`.category-filter[data-customer-id="${customerId}"]`);
    
    if (customerOption) {
      customerOption.click();
      
      setTimeout(() => {
        displayToast('Customer selected from QR scan!', 'success', 2000);
      }, 300);
    } else {
      console.warn('Customer not found in dropdown');
      alert('Customer found but not available in the dropdown list.');
    }
  }
  
  window.selectCustomerInDropdown = selectCustomerInDropdown;
  
  // Check for scanned customer
  const scannedCustomerId = localStorage.getItem('scannedCustomerId');
  
  if (scannedCustomerId) {
    setTimeout(() => {
      selectCustomerInDropdown(scannedCustomerId);
      
      localStorage.removeItem('scannedCustomerId');
      localStorage.removeItem('scannedCustomerName');
      localStorage.removeItem('scannedCustomerSerial');
      localStorage.removeItem('scannedCustomerNumber');
    }, 500);
  }

  // Initialize button data
  addButtons.forEach((button, index) => {
    const productName = button.getAttribute('data-name');
    const productId = button.getAttribute('data-id');
    buttonToProduct.set(button, {
      id: productId,
      name: productName,
      price: parseFloat(button.getAttribute('data-price')),
      image: button.getAttribute('data-image'),
      containerId: button.getAttribute('data-container') || button.closest('[id^="container-"]')?.id || `container-${productId}`
    });
    
    buttonQuantities.set(button, 0);
    
    if (productName.toLowerCase().includes('stove')) {
      const colorMap = {};
      availableColors.forEach(color => {
        colorMap[color] = 0;
      });
      stoveColorQuantities.set(button, colorMap);
    }
    
    updateButtonDisplay(button, 0);
  });

  // Load saved cart
  loadSavedCart();

  // Button click handlers
  addButtons.forEach(button => {
    button.addEventListener('click', function() {
      const productData = buttonToProduct.get(this);
      if (!productData) return;

      const { name, price, image } = productData;

      if (currentExpansion) closeExpansion();

      if (name.toLowerCase().includes('stove')) {
        showStoveModal(this, name, price, image);
      } else {
        showQuantityModal(this, name, price, image);
      }

      this.style.transform = 'scale(0.9)';
      setTimeout(() => {
        this.style.transform = 'scale(1)';
      }, 150);
    });
  });

  // Cart update functions
  function updateCartAndTriggerHeaderUpdate() {
    saveCartToLocalStorage();
    
    if (typeof window.updateCartBadge === 'function') {
      window.updateCartBadge();
    }
    if (typeof window.updateFloatingCartButton === 'function') {
      window.updateFloatingCartButton();
    }
    
    window.dispatchEvent(new CustomEvent('cartUpdated', {
      detail: { 
        items: cart.items, 
        amount: cart.amount, 
        products: cart.products.length 
      }
    }));
  }

  function saveCartToLocalStorage() {
    try {
      localStorage.setItem('dealerCartData', JSON.stringify(cart.products));
      localStorage.setItem('dealerCartTotal', cart.amount.toFixed(2));
      localStorage.setItem('dealerCartItems', cart.items.toString());
      
      localStorage.setItem('cartData', JSON.stringify(cart.products));
      localStorage.setItem('cartTotal', cart.amount.toFixed(2));
      localStorage.setItem('cartItems', cart.items.toString());
      
      console.log('Cart saved to localStorage:', {
        items: cart.items,
        amount: cart.amount,
        products: cart.products.length
      });
      
      return true;
    } catch (error) {
      console.error('Error saving cart:', error);
      return false;
    }
  }

  function updateRegularProductCart(productData, button, oldQuantity, newQuantity) {
    const quantityDiff = newQuantity - oldQuantity;
    const priceDiff = productData.price * quantityDiff;
    
    cart.items += quantityDiff;
    cart.amount += priceDiff;
    
    const buttonId = Array.from(addButtons).indexOf(button);
    
    const existingProductIndex = cart.products.findIndex(p => 
      p.name === productData.name && p.buttonId === buttonId
    );
    
    if (newQuantity === 0) {
      if (existingProductIndex !== -1) {
        cart.products.splice(existingProductIndex, 1);
      }
    } else if (existingProductIndex !== -1) {
      cart.products[existingProductIndex].quantity = newQuantity;
    } else {
      cart.products.push({
        id: productData.id,
        name: productData.name,
        originalName: productData.name,
        price: productData.price,
        quantity: newQuantity,
        image: productData.image,
        buttonId: buttonId
      });
    }
    
    updateCartAndTriggerHeaderUpdate();
  }

  function updateStoveProductCart(productData, button, oldTotalQuantity, newTotalQuantity, colorQuantities) {
    const buttonId = Array.from(addButtons).indexOf(button);
    
    cart.products = cart.products.filter(p => 
      !(p.originalName === productData.name && p.buttonId === buttonId)
    );
    
    cart.items -= oldTotalQuantity;
    cart.amount -= (productData.price * oldTotalQuantity);
    
    Object.entries(colorQuantities).forEach(([color, quantity]) => {
      if (quantity > 0) {
        const productIdentifier = `${productData.name} (${color})`;
        cart.products.push({
          id: productData.id,
          name: productIdentifier,
          originalName: productData.name,
          price: productData.price,
          quantity: quantity,
          image: productData.image,
          color: color,
          buttonId: buttonId
        });
        
        cart.items += quantity;
        cart.amount += (productData.price * quantity);
      }
    });
    
    updateCartAndTriggerHeaderUpdate();
  }

  function loadSavedCart() {
    try {
      const savedProducts = localStorage.getItem('dealerCartData');
      const savedItems = localStorage.getItem('dealerCartItems');
      const savedTotal = localStorage.getItem('dealerCartTotal');
      
      if (savedProducts) {
        cart.products = JSON.parse(savedProducts);
        cart.items = parseInt(savedItems) || 0;
        cart.amount = parseFloat(savedTotal) || 0;
        
        console.log('Found saved cart:', cart);
        
        updateButtonsFromSavedData();
        updateCartAndTriggerHeaderUpdate();
      }
    } catch (error) {
      console.error('Error loading saved cart:', error);
      cart = { items: 0, amount: 0, products: [] };
    }
  }

  function updateButtonsFromSavedData() {
    addButtons.forEach((button, buttonIndex) => {
      const productData = buttonToProduct.get(button);
      if (!productData) return;
      
      const matchingProducts = cart.products.filter(product => {
        return (product.originalName === productData.name || product.name.includes(productData.name)) 
               && (product.buttonId === buttonIndex || !product.buttonId);
      });
      
      if (matchingProducts.length > 0) {
        let totalQuantity = 0;
        
        if (productData.name.toLowerCase().includes('stove')) {
          const colorMap = {};
          availableColors.forEach(color => colorMap[color] = 0);
          
          matchingProducts.forEach(product => {
            if (product.color) {
              colorMap[product.color] = (colorMap[product.color] || 0) + product.quantity;
              totalQuantity += product.quantity;
            } else {
              totalQuantity += product.quantity;
            }
          });
          
          stoveColorQuantities.set(button, colorMap);
        } else {
          totalQuantity = matchingProducts.reduce((sum, product) => sum + product.quantity, 0);
        }
        
        buttonQuantities.set(button, totalQuantity);
        updateButtonDisplay(button, totalQuantity);
      }
    });
  }

  // Toast notification
  function displayToast(message, type, duration) {
    const toast = document.createElement('div');
    toast.className = 'toast ' + type;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 100);
    
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => {
        if (toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 300);
    }, duration || 3000);
  }

  // Quantity modal for regular products
  function showQuantityModal(button, productName, price, productImage) {
    const currentQuantity = buttonQuantities.get(button) || 0;
    
    const existingModal = document.querySelector('.quantity-modal');
    if (existingModal) existingModal.remove();

    const modalHTML = `
      <div class="color-selection-expansion quantity-modal" data-product-name="${productName}" data-product-price="${price}" data-product-image="${productImage}">
        <div class="expansion-header">
          <h4 class="expansion-title">Select Quantity</h4>
          <button class="close-expansion">
            <i class="bi bi-x"></i>
          </button>
        </div>
        
        <div class="quantity-selection">
          <div class="product-info-modal">
            <img src="${productImage}" alt="${productName}" class="modal-product-image" 
                 onerror="this.src='${window.location.origin}/images/stovewcyllinder.jpg'">
            <div class="modal-product-details">
              <div class="modal-product-name">${productName}</div>
              <div class="modal-product-price">₱ ${price.toFixed(2)}</div>
            </div>
          </div>
          
          <div class="quantity-input-section">
            <label class="quantity-label">Quantity:</label>
            <div class="current_cart_qty" style="font-size: 12px; color: #666; margin-bottom: 10px;">
                Currently in cart: ${currentQuantity}
            </div>
            <div class="quantity-controls">
              <button type="button" class="quantity-btn minus-btn" data-action="decrease">-</button>
              <input type="number" class="quantity-input" placeholder="0" min="0" value="${currentQuantity}" max="999">
              <button type="button" class="quantity-btn plus-btn" data-action="increase">+</button>
            </div>
          </div>
        </div>
        
        <div class="expansion-total">
          <span class="expansion-total-label">Total:</span>
          <span class="expansion-total-price">₱ ${(price * currentQuantity).toFixed(2)}</span>
        </div>
        
        <div class="modal-actions">
          <button class="modal-action-btn cancel-btn">Cancel</button>
          <button class="modal-action-btn confirm-btn">Confirm</button>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const modal = document.querySelector('.quantity-modal');
    currentExpansion = modal;
    
    expansionOverlay.classList.add('active');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    setupQuantityModalEvents(modal, price, button);
  }

  function setupQuantityModalEvents(modal, basePrice, button) {
    const quantityInput = modal.querySelector('.quantity-input');
    const totalPrice = modal.querySelector('.expansion-total-price');
    const confirmBtn = modal.querySelector('.confirm-btn');
    const cancelBtn = modal.querySelector('.cancel-btn');
    const closeBtn = modal.querySelector('.close-expansion');
    const plusBtn = modal.querySelector('.plus-btn');
    const minusBtn = modal.querySelector('.minus-btn');

    function updateTotal() {
      const quantity = parseInt(quantityInput.value) || 0;
      const total = basePrice * quantity;
      totalPrice.textContent = `₱ ${total.toFixed(2)}`;
    }

    plusBtn.addEventListener('click', () => {
      const currentValue = parseInt(quantityInput.value) || 0;
      quantityInput.value = Math.min(currentValue + 1, 999);
      updateTotal();
    });

    minusBtn.addEventListener('click', () => {
      const currentValue = parseInt(quantityInput.value) || 0;
      quantityInput.value = Math.max(currentValue - 1, 0);
      updateTotal();
    });

    quantityInput.addEventListener('input', () => {
      let value = parseInt(quantityInput.value) || 0;
      value = Math.max(0, Math.min(value, 999));
      quantityInput.value = value;
      updateTotal();
    });

    confirmBtn.addEventListener('click', () => {
      const newQuantity = parseInt(quantityInput.value) || 0;
      const productData = buttonToProduct.get(button);
      
      if (!productData) return;

      const oldQuantity = buttonQuantities.get(button) || 0;
      const addedQuantity = newQuantity - oldQuantity;
      
      buttonQuantities.set(button, newQuantity);
      
      updateRegularProductCart(productData, button, oldQuantity, newQuantity);
      updateButtonDisplay(button, newQuantity);
      
      if (addedQuantity > 0) {
        displayToast(`${addedQuantity} item(s) added to cart!`, 'success');
      } else if (addedQuantity < 0) {
        displayToast(`${Math.abs(addedQuantity)} item(s) removed from cart!`, 'info');
      }
      
      closeExpansion();
    });

    cancelBtn.addEventListener('click', closeExpansion);
    closeBtn.addEventListener('click', closeExpansion);
  }

  // Stove modal with color options
  function showStoveModal(button, productName, price, productImage) {
    const existingModal = document.querySelector('.dynamic-color-modal');
    if (existingModal) existingModal.remove();

    const colorOptionsHTML = availableColors.map(color => `
      <div class="color-option-row">
        <span class="color-label">${color.charAt(0).toUpperCase() + color.slice(1)}</span>
        <div class="color-input-wrapper">
          <span class="qty-stock text-mute">0</span>
          <input type="number" class="color-quantity-input" data-color="${color}" min="0" value="0" max="999">
        </div>
      </div>
    `).join('');

    const modalHTML = `
      <div class="color-selection-expansion dynamic-color-modal" data-product-name="${productName}" data-product-price="${price}" data-product-image="${productImage}">
        <div class="expansion-header">
          <h4 class="expansion-title">Choose Color & Quantity</h4>
          <button class="close-expansion">
            <i class="bi bi-x"></i>
          </button>
        </div>
        
        <div class="color-options">
          ${colorOptionsHTML}
        </div>
        
        <div class="expansion-total">
          <span class="expansion-total-label">Total:</span>
          <span class="expansion-total-price">₱ 0.00</span>
        </div>
        
        <button class="add-to-cart-expansion" disabled>Confirm</button>
      </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const modal = document.querySelector('.dynamic-color-modal');
    currentExpansion = modal;
    
    loadColorQuantitiesInModal(modal, button);
    
    expansionOverlay.classList.add('active');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    setupStoveModalEvents(modal, price, button);
  }

  function loadColorQuantitiesInModal(modal, button) {
    const colorQuantities = stoveColorQuantities.get(button) || {};
    const quantityInputs = modal.querySelectorAll('.color-quantity-input');
    
    quantityInputs.forEach(input => {
      const color = input.getAttribute('data-color');
      const existingQuantity = colorQuantities[color] || 0;
      input.value = existingQuantity;
    });
    
    const price = parseFloat(modal.dataset.productPrice);
    updateStoveModalTotal(modal, price);
  }

  function setupStoveModalEvents(modal, basePrice, button) {
    const quantityInputs = modal.querySelectorAll('.color-quantity-input');
    const addToCartBtn = modal.querySelector('.add-to-cart-expansion');
    const closeBtn = modal.querySelector('.close-expansion');
    
    quantityInputs.forEach(input => {
      input.addEventListener('input', function() {
        if (parseInt(this.value) < 0) {
          this.value = 0;
        }
        updateStoveModalTotal(modal, basePrice);
      });

      input.addEventListener('blur', function() {
        if (this.value === '' || isNaN(parseInt(this.value))) {
          this.value = 0;
        }
        updateStoveModalTotal(modal, basePrice);
      });
    });
    
    addToCartBtn.addEventListener('click', function() {
      const productData = buttonToProduct.get(button);
      if (!productData) return;
      
      saveColorQuantitiesFromModal(modal, button);
      
      const colorQuantities = stoveColorQuantities.get(button) || {};
      const newTotalQuantity = Object.values(colorQuantities).reduce((sum, qty) => sum + qty, 0);
      const oldTotalQuantity = buttonQuantities.get(button) || 0;
      
      buttonQuantities.set(button, newTotalQuantity);
      
      updateStoveProductCart(productData, button, oldTotalQuantity, newTotalQuantity, colorQuantities);
      
      updateButtonDisplay(button, newTotalQuantity);
      
      if (newTotalQuantity > 0) {
        displayToast(`${newTotalQuantity} item(s) added to cart!`, 'success');
      }
      
      closeExpansion();
    });
    
    closeBtn.addEventListener('click', () => {
      saveColorQuantitiesFromModal(modal, button);
      
      const colorQuantities = stoveColorQuantities.get(button) || {};
      const totalQuantity = Object.values(colorQuantities).reduce((sum, qty) => sum + qty, 0);
      buttonQuantities.set(button, totalQuantity);
      updateButtonDisplay(button, totalQuantity);
      
      closeExpansion();
    });
  }

  function saveColorQuantitiesFromModal(modal, button) {
    const quantityInputs = modal.querySelectorAll('.color-quantity-input');
    const colorQuantities = stoveColorQuantities.get(button) || {};
    
    quantityInputs.forEach(input => {
      const color = input.getAttribute('data-color');
      const quantity = parseInt(input.value) || 0;
      colorQuantities[color] = quantity;
    });
    
    stoveColorQuantities.set(button, colorQuantities);
  }

  function updateStoveModalTotal(modal, basePrice) {
    const quantityInputs = modal.querySelectorAll('.color-quantity-input');
    const totalPrice = modal.querySelector('.expansion-total-price');
    const addToCartBtn = modal.querySelector('.add-to-cart-expansion');
    
    let totalQuantity = 0;
    
    quantityInputs.forEach(input => {
      const quantity = parseInt(input.value) || 0;
      totalQuantity += quantity;
    });
    
    const total = basePrice * totalQuantity;
    totalPrice.textContent = `₱ ${total.toFixed(2)}`;
    addToCartBtn.disabled = totalQuantity === 0;
  }

  function updateButtonDisplay(button, quantity) {
    const productData = buttonToProduct.get(button);
    const isStove = productData && productData.name.toLowerCase().includes('stove');
    
    if (quantity > 0) {
      button.innerHTML = `<span class="quantity-badge">${quantity}</span>`;
      button.classList.add('has-quantity');
      
      if (isStove) {
        button.classList.add('stove-product');
      }
    } else {
      button.innerHTML = `<i class="bi bi-plus"></i>`;
      button.classList.remove('has-quantity', 'stove-product');
    }
  }

  function closeExpansion() {
    if (currentExpansion) {
      currentExpansion.classList.remove('active');
      
      setTimeout(() => {
        if (currentExpansion && (
          currentExpansion.classList.contains('dynamic-color-modal') ||
          currentExpansion.classList.contains('quantity-modal')
        )) {
          currentExpansion.remove();
        }
      }, 300);
      
      currentExpansion = null;
    }
    
    expansionOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  // Event listeners
  expansionOverlay.addEventListener('click', closeExpansion);
  
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && currentExpansion) {
      closeExpansion();
    }
  });

  // Initialize header cart updates
  setTimeout(() => {
    if (typeof window.updateCartBadge === 'function') {
      window.updateCartBadge();
    }
    if (typeof window.updateFloatingCartButton === 'function') {
      window.updateFloatingCartButton();
    }
  }, 100);
});

// ===== GLOBAL FUNCTIONS FOR HEADER INTEGRATION =====

window.triggerCartUpdate = function() {
  if (typeof window.updateCartBadge === 'function') {
    window.updateCartBadge();
  }
  if (typeof window.updateFloatingCartButton === 'function') {
    window.updateFloatingCartButton();
  }
};

// Listen for storage changes
window.addEventListener('storage', function(e) {
  if (e.key === 'dealerCartData' || e.key === 'dealerCartItems' || e.key === 'dealerCartTotal') {
    window.triggerCartUpdate();
  }
});

// Custom event listener for cart updates
window.addEventListener('cartUpdated', function(e) {
  console.log('Cart updated event received:', e.detail);
});
</script>
@endsection