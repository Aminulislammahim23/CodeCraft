const courses = [
    { ratings: [], reviews: [] },
    { ratings: [], reviews: [] }
  ];

  document.querySelectorAll('.stars').forEach(starDiv => {
    const stars = starDiv.querySelectorAll('span');
    stars.forEach(star => {
      star.addEventListener('mouseover', () => {
        stars.forEach(s => s.classList.remove('hover'));
        for(let i=0; i<star.dataset.value; i++) {
          stars[i].classList.add('hover');
        }
      });
      star.addEventListener('mouseout', () => {
        stars.forEach(s => s.classList.remove('hover'));
      });
      star.addEventListener('click', () => {
        const index = starDiv.dataset.course;
        courses[index].selectedRating = parseInt(star.dataset.value);
        updateStars(starDiv, courses[index].selectedRating);
      });
    });
  });

  function updateStars(starDiv, rating) {
    const stars = starDiv.querySelectorAll('span');
    stars.forEach((s,i) => {
      if(i<rating) s.classList.add('selected');
      else s.classList.remove('selected');
    });
  }

  function submitReview(index) {
    const card = document.querySelectorAll('.course-card')[index];
    const review = card.querySelector('textarea').value;
    const rating = courses[index].selectedRating || 0;
    if(rating === 0) {
      alert("Please select a rating before submitting.");
      return;
    }
    courses[index].ratings.push(rating);
    courses[index].reviews.push(review);
    card.querySelector('.avg-rating').innerText = (courses[index].ratings.reduce((a,b)=>a+b,0)/courses[index].ratings.length).toFixed(1);
    alert("Review submitted!");
    card.querySelector('textarea').value = '';
  }

  document.getElementById('filter').addEventListener('change', e => {
    const minRating = parseInt(e.target.value);
    document.querySelectorAll('.course-card').forEach((card,i) => {
      const avg = courses[i].ratings.length ? courses[i].ratings.reduce((a,b)=>a+b,0)/courses[i].ratings.length : 0;
      if(avg >= minRating) card.style.display = 'block';
      else card.style.display = 'none';
    });
  });