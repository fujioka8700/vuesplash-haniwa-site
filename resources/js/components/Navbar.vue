<template>
  <header>
    <nav class="navbar">
      <RouterLink class="navbar__brand" to="/">
        Vuesplash
      </RouterLink>
      <div class="navbar__menu">
        <div class="navbar__item">
          <button class="button">
            <i class="icon ion-md-add"></i>
            Submit a phpto
          </button>
        </div>
        <span class="navbar__item">
          user name
        </span>
      </div>
      <div class="navbar__item">
        <RouterLink class="button button--link" to="/login">
          Login / Register
        </RouterLink>
      </div>
    </nav>
  </header>
</template>
