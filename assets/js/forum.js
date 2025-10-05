const forumData = {
  posts: [
    {
      id: 1,
      title: "How to center a div in CSS?",
      content: "I'm having trouble centering a div both horizontally and vertically...",
      author: "JaneSmith",
      authorAvatar: "https://via.placeholder.com/30",
      course: "Web Development 101",
      date: "2023-05-15",
      replies: [
        {
          id: 101,
          author: "MikeJohnson",
          content: "You can use flexbox for this...",
          isInstructor: false,
          upvotes: 12
        },
        {
          id: 102,
          author: "ProfWilliams",
          content: "Mike's answer is correct...",
          isInstructor: true,
          upvotes: 8
        }
      ],
      views: 145,
      upvotes: 5
    },
    
  ],
  currentUser: {
    name: "JohnDoe",
    isInstructor: false
  }
};

// Get all the elements we need from the page
const elements = {
  forumList: document.querySelector('.forum-list'),
  newPostBtn: document.getElementById('newPostBtn'),
  modals: {
    newPost: document.getElementById('newPostModal'),
    postDetail: document.getElementById('postDetailModal')
  },
  forms: {
    newPost: document.getElementById('newPostForm'),
    reply: document.getElementById('replyContent')
  },
  postDetail: {
    title: document.getElementById('postDetailTitle'),
    content: document.getElementById('postDetailContent'),
    author: document.getElementById('postAuthor'),
    course: document.getElementById('postCourseName'),
    date: document.getElementById('postDate'),
    repliesList: document.getElementById('repliesList')
  }
};

// Keep track of which post we're currently viewing
let currentPostId = null;

// Helper functions to make our code cleaner
const helpers = {
  // Format dates nicely
  formatDate: (dateString) => {
    return new Date(dateString).toLocaleDateString();
  },
  
  // Count replies for a post
  countReplies: (post) => {
    return post.replies.length;
  },
  
  // Get the last reply date
  getLastReplyDate: (post) => {
    const replies = post.replies;
    return replies.length > 0 ? 
      helpers.formatDate(replies[replies.length-1].date) : 
      'No replies yet';
  }
};

// Functions to handle showing posts
const postUI = {
  // Show all posts in the forum list
  showAllPosts: () => {
    elements.forumList.innerHTML = '';
    
    forumData.posts.forEach(post => {
      const postElement = document.createElement('div');
      postElement.className = 'forum-item';
      postElement.dataset.id = post.id;
      
      postElement.innerHTML = `
        <h3>${post.title}</h3>
        <p>${post.content.substring(0, 100)}...</p>
        <div class="forum-meta">
          <span class="author">
            <img src="${post.authorAvatar}" alt="${post.author}">
            ${post.author}
          </span>
          <span class="stats">
            <span><i class="far fa-comment"></i> ${helpers.countReplies(post)}</span>
            <span><i class="far fa-eye"></i> ${post.views}</span>
            <span><i class="far fa-thumbs-up"></i> ${post.upvotes}</span>
            <span><i class="far fa-clock"></i> ${helpers.getLastReplyDate(post)}</span>
          </span>
        </div>
      `;
      
      postElement.addEventListener('click', () => postUI.showPostDetail(post.id));
      elements.forumList.appendChild(postElement);
    });
  },
  
  // Show details for a single post
  showPostDetail: (postId) => {
    currentPostId = postId;
    const post = forumData.posts.find(p => p.id == postId);
    
    if (!post) return;
    
    // Update the post details
    elements.postDetail.title.textContent = post.title;
    elements.postDetail.content.textContent = post.content;
    elements.postDetail.author.textContent = post.author;
    elements.postDetail.course.textContent = post.course;
    elements.postDetail.date.textContent = helpers.formatDate(post.date);
    
    // Show all replies
    elements.postDetail.repliesList.innerHTML = '';
    post.replies.forEach(reply => {
      const replyElement = document.createElement('div');
      replyElement.className = 'reply-item';
      
      replyElement.innerHTML = `
        <div class="reply-meta">
          <div class="reply-author">
            <img src="${reply.authorAvatar || forumData.currentUser.avatar}" alt="${reply.author}">
            ${reply.author}
            ${reply.isInstructor ? '<span class="instructor-badge">Instructor</span>' : ''}
          </div>
          <span>${helpers.formatDate(reply.date)}</span>
        </div>
        <div class="reply-content">${reply.content}</div>
        <div class="reply-actions">
          <button class="btn"><i class="far fa-thumbs-up"></i> ${reply.upvotes}</button>
        </div>
      `;
      
      elements.postDetail.repliesList.appendChild(replyElement);
    });
    
    // Show the modal
    elements.modals.postDetail.style.display = 'block';
  }
};

// Functions to handle creating new content
const postActions = {
  // Create a new forum post
  createNewPost: (e) => {
    e.preventDefault();
    
    const title = document.getElementById('postTitle').value;
    const courseSelect = document.getElementById('postCourse');
    const course = courseSelect.options[courseSelect.selectedIndex].text;
    const content = document.getElementById('postContent').value;
    
    const newPost = {
      id: forumData.posts.length + 1,
      title: title,
      content: content,
      author: forumData.currentUser.name,
      authorAvatar: forumData.currentUser.avatar,
      course: course,
      date: new Date().toISOString().split('T')[0],
      replies: [],
      views: 0,
      upvotes: 0
    };
    
    // Add to beginning of array (newest first)
    forumData.posts.unshift(newPost);
    
    // Refresh the post list
    postUI.showAllPosts();
    
    // Reset and close the form
    elements.forms.newPost.reset();
    elements.modals.newPost.style.display = 'none';
  },
  
  // Add a reply to a post
  addReply: () => {
    const content = elements.forms.reply.value.trim();
    
    if (!content || !currentPostId) return;
    
    const post = forumData.posts.find(p => p.id == currentPostId);
    if (!post) return;
    
    const newReply = {
      id: post.replies.length + 1,
      author: forumData.currentUser.name,
      authorAvatar: forumData.currentUser.avatar,
      content: content,
      date: new Date().toISOString().split('T')[0],
      isInstructor: forumData.currentUser.isInstructor,
      upvotes: 0
    };
    
    post.replies.push(newReply);
    
    // Refresh the post detail view
    postUI.showPostDetail(currentPostId);
    
    // Clear the reply form
    elements.forms.reply.value = '';
  }
};

// Functions to handle modal windows
const modalUI = {
  // Open the new post modal
  openNewPostModal: () => {
    elements.modals.newPost.style.display = 'block';
  },
  
  // Close any modal
  closeModal: (modal) => {
    modal.style.display = 'none';
  },
  
  // Close modals when clicking outside
  handleOutsideClick: (e) => {
    if (e.target === elements.modals.newPost) {
      modalUI.closeModal(elements.modals.newPost);
    }
    if (e.target === elements.modals.postDetail) {
      modalUI.closeModal(elements.modals.postDetail);
    }
  }
};

// Set up all our event listeners
const setupEventListeners = () => {
  // New post button
  elements.newPostBtn.addEventListener('click', modalUI.openNewPostModal);
  
  // New post form submission
  elements.forms.newPost.addEventListener('submit', postActions.createNewPost);
  
  // Reply button
  document.getElementById('submitReply').addEventListener('click', postActions.addReply);
  
  // Close buttons
  document.querySelectorAll('.close').forEach(button => {
    button.addEventListener('click', () => {
      const modal = button.closest('.modal');
      modalUI.closeModal(modal);
    });
  });
  
  // Click outside modals to close
  window.addEventListener('click', modalUI.handleOutsideClick);
};

// Initialize the forum when the page loads
const initForum = () => {
  setupEventListeners();
  postUI.showAllPosts();
};

// Start everything when the page is ready
document.addEventListener('DOMContentLoaded', initForum);