document.getElementById('logo-btn').addEventListener('click', function(event) {
  event.preventDefault();
    const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('collapsed'); 
});

function dateFilter(){
  const currentYear = new Date().getFullYear();
    const startYear = currentYear - 1;
    const endYear = currentYear - 5; 

    const dateFilter = document.getElementById('dateFilter');
      for (let year = startYear; year >= endYear; year--) {
        const option = document.createElement('option');
        option.value = year;  
        option.textContent = year;  
        dateFilter.appendChild(option); 
      }
}