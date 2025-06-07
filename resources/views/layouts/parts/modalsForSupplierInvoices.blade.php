<!-- مودال عرض الفاتورة -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-labelledby="viewInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewInvoiceModalLabel">عرض الفاتورة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <div class="modal-body">
        <p><strong>رقم الفاتورة:</strong> <span id="view-invoice-no"></span></p>
        <p><strong>المورد:</strong> <span id="view-supplier"></span></p>
        <p><strong>المخزن:</strong> <span id="view-store"></span></p>
        <p><strong>الإجمالي:</strong> <span id="view-total"></span></p>
        <p><strong>الخصم:</strong> <span id="view-discount"></span></p>
        <p><strong>الصافي:</strong> <span id="view-net-total"></span></p>
        <p><strong>عدد القطع:</strong> <span id="view-pieces-no"></span></p>
        <p><strong>تاريخ الفاتورة:</strong> <span id="view-invoice-date"></span></p>
        <p><strong>ملاحظات:</strong> <span id="view-notes"></span></p>
        <p><strong>تاريخ الإنشاء:</strong> <span id="view-created"></span></p>
        <p><strong>تاريخ التحديث:</strong> <span id="view-updated"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>
    </div>
  </div>
</div>



<!-- مودال تعديل الفاتورة -->
<div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editInvoiceForm" method="POST" action="">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editInvoiceModalLabel">تعديل الفاتورة</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit-invoice-id" name="id">

          <div class="mb-3">
            <label for="edit-invoice-no" class="form-label">رقم الفاتورة</label>
            <input type="text" class="form-control" id="edit-invoice-no" name="invoice_no" required>
          </div>

          <div class="mb-3">
            <label for="edit-supplier-id" class="form-label">المورد</label>
            <select id="edit-supplier-id" name="supplier_id" class="form-control" required>
              <!-- options dynamically loaded -->
            </select>
          </div>

          <div class="mb-3">
            <label for="edit-store-id" class="form-label">المخزن</label>
            <select id="edit-store-id" name="store_id" class="form-control" required>
              <!-- options dynamically loaded -->
            </select>
          </div>

          <div class="mb-3">
            <label for="edit-total" class="form-label">الإجمالي</label>
            <input type="number" step="0.01" class="form-control" id="edit-total" name="total" required>
          </div>

          <div class="mb-3">
            <label for="edit-discount" class="form-label">الخصم</label>
            <input type="number" step="0.01" class="form-control" id="edit-discount" name="discount">
          </div>

          <div class="mb-3">
            <label for="edit-net-total" class="form-label">الصافي</label>
            <input type="number" step="0.01" class="form-control" id="edit-net-total" name="net_total" required>
          </div>

          <div class="mb-3">
            <label for="edit-pieces-no" class="form-label">عدد القطع</label>
            <input type="number" class="form-control" id="edit-pieces-no" name="pieces_no">
          </div>

          <div class="mb-3">
            <label for="edit-invoice-date" class="form-label">تاريخ الفاتورة</label>
            <input type="date" class="form-control" id="edit-invoice-date" name="invoice_date" required>
          </div>

          <div class="mb-3">
            <label for="edit-notes" class="form-label">ملاحظات</label>
            <textarea class="form-control" id="edit-notes" name="notes"></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">حفظ التعديل</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
        </div>
      </div>
    </form>
  </div>
</div>
