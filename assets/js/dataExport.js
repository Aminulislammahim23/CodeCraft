<script>
  document.querySelector('.export-form').addEventListener('submit', function(event) {
    const yearSelect = document.getElementById('year');
    if (!yearSelect.value) {
      alert('Please select a year before exporting.');
      event.preventDefault();
      return false;
    }

    const confirmExport = confirm(`Do you want to export revenue data for the year ${yearSelect.value}?`);
    if (!confirmExport) {
      event.preventDefault();
      return false;
    }
  });
</script>
