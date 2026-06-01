/* ============================================================
   Contact Tracing System — University of San Carlos, Cebu
   USC Brand Colors: Royal Blue #003087 + Gold #C9A84C
   ============================================================ */

*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

:root {
    --usc-blue:       #003087;
    --usc-blue-dark:  #001f5a;
    --usc-blue-mid:   #0044b3;
    --usc-blue-light: #e8edf7;
    --usc-gold:       #C9A84C;
    --usc-gold-dark:  #a07830;
    --usc-gold-light: #fdf5e0;
    --usc-gold-pale:  #fef9ee;
    --white:          #ffffff;
    --gray-bg:        #f5f6fa;
    --gray-border:    #dde1ec;
    --gray-text:      #3a3a3a;
    --gray-muted:     #6b7280;
    --red:            #c0392b;
    --green:          #1a7a40;
    --shadow-sm:      0 1px 4px rgba(0,48,135,.10);
    --shadow:         0 3px 16px rgba(0,48,135,.12);
    --shadow-lg:      0 6px 30px rgba(0,48,135,.18);
    --radius:         10px;
    --radius-sm:      6px;
}

/* ─── Typography ─────────────────────────────────────────── */
body {
    font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
    background-color: #e8edf8;
    background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPSczMDAnIGhlaWdodD0nMzAwJz4KICA8bGluZSB4MT0nMCcgeTE9Jzc1JyB4Mj0nMTEwJyB5Mj0nNzUnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScxLjUnIG9wYWNpdHk9JzAuMDknLz4KICA8bGluZSB4MT0nMTQwJyB5MT0nNzUnIHgyPSczMDAnIHkyPSc3NScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4wOScvPgogIDxsaW5lIHgxPScwJyB5MT0nMTUwJyB4Mj0nNjAnIHkyPScxNTAnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScxLjUnIG9wYWNpdHk9JzAuMDknLz4KICA8bGluZSB4MT0nOTAnIHkxPScxNTAnIHgyPScyMTAnIHkyPScxNTAnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScxLjUnIG9wYWNpdHk9JzAuMDknLz4KICA8bGluZSB4MT0nMjQwJyB5MT0nMTUwJyB4Mj0nMzAwJyB5Mj0nMTUwJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGxpbmUgeDE9JzAnIHkxPScyMjUnIHgyPSc1MCcgeTI9JzIyNScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4wOScvPgogIDxsaW5lIHgxPSc4MCcgeTE9JzIyNScgeDI9JzE4NScgeTI9JzIyNScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4wOScvPgogIDxsaW5lIHgxPScyMTUnIHkxPScyMjUnIHgyPSczMDAnIHkyPScyMjUnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScxLjUnIG9wYWNpdHk9JzAuMDknLz4KICA8bGluZSB4MT0nNzUnIHkxPScwJyB4Mj0nNzUnIHkyPSc1NScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4wOScvPgogIDxsaW5lIHgxPSc3NScgeTE9Jzk1JyB4Mj0nNzUnIHkyPScxMzAnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScxLjUnIG9wYWNpdHk9JzAuMDknLz4KICA8bGluZSB4MT0nNzUnIHkxPScxNzAnIHgyPSc3NScgeTI9JzIwNScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4wOScvPgogIDxsaW5lIHgxPSc3NScgeTE9JzI0NScgeDI9Jzc1JyB5Mj0nMzAwJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGxpbmUgeDE9JzE1MCcgeTE9JzAnIHgyPScxNTAnIHkyPSc1NScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4wOScvPgogIDxsaW5lIHgxPScxNTAnIHkxPSc5NScgeDI9JzE1MCcgeTI9JzEzMCcgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4wOScvPgogIDxsaW5lIHgxPScxNTAnIHkxPScxNzAnIHgyPScxNTAnIHkyPScyMDUnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScxLjUnIG9wYWNpdHk9JzAuMDknLz4KICA8bGluZSB4MT0nMTUwJyB5MT0nMjQ1JyB4Mj0nMTUwJyB5Mj0nMzAwJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGxpbmUgeDE9JzIyNScgeTE9JzAnIHgyPScyMjUnIHkyPScxMzAnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScxLjUnIG9wYWNpdHk9JzAuMDknLz4KICA8bGluZSB4MT0nMjI1JyB5MT0nMTcwJyB4Mj0nMjI1JyB5Mj0nMzAwJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGNpcmNsZSBjeD0nNzUnIGN5PSc3NScgcj0nNicgZmlsbD0nbm9uZScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzInIG9wYWNpdHk9JzAuMTQnLz4KICA8Y2lyY2xlIGN4PSc3NScgY3k9JzE1MCcgcj0nNicgZmlsbD0nbm9uZScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzInIG9wYWNpdHk9JzAuMTQnLz4KICA8Y2lyY2xlIGN4PSc3NScgY3k9JzIyNScgcj0nNicgZmlsbD0nbm9uZScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzInIG9wYWNpdHk9JzAuMTQnLz4KICA8Y2lyY2xlIGN4PScxNTAnIGN5PSc3NScgcj0nNicgZmlsbD0nbm9uZScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzInIG9wYWNpdHk9JzAuMTQnLz4KICA8Y2lyY2xlIGN4PScxNTAnIGN5PScxNTAnIHI9JzYnIGZpbGw9J25vbmUnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScyJyBvcGFjaXR5PScwLjE0Jy8+CiAgPGNpcmNsZSBjeD0nMTUwJyBjeT0nMjI1JyByPSc2JyBmaWxsPSdub25lJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMicgb3BhY2l0eT0nMC4xNCcvPgogIDxjaXJjbGUgY3g9JzIyNScgY3k9Jzc1JyByPSc2JyBmaWxsPSdub25lJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMicgb3BhY2l0eT0nMC4xNCcvPgogIDxjaXJjbGUgY3g9JzIyNScgY3k9JzE1MCcgcj0nNicgZmlsbD0nbm9uZScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzInIG9wYWNpdHk9JzAuMTQnLz4KICA8Y2lyY2xlIGN4PScyMjUnIGN5PScyMjUnIHI9JzYnIGZpbGw9J25vbmUnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScyJyBvcGFjaXR5PScwLjE0Jy8+CiAgPGNpcmNsZSBjeD0nNzUnIGN5PSc3NScgcj0nMicgZmlsbD0nIzAwMzA4Nycgb3BhY2l0eT0nMC4xMicvPgogIDxjaXJjbGUgY3g9JzE1MCcgY3k9JzE1MCcgcj0nMicgZmlsbD0nIzAwMzA4Nycgb3BhY2l0eT0nMC4xMicvPgogIDxjaXJjbGUgY3g9JzIyNScgY3k9JzIyNScgcj0nMicgZmlsbD0nIzAwMzA4Nycgb3BhY2l0eT0nMC4xMicvPgogIDxjaXJjbGUgY3g9Jzc1JyBjeT0nMjI1JyByPScyJyBmaWxsPScjMDAzMDg3JyBvcGFjaXR5PScwLjEyJy8+CiAgPGNpcmNsZSBjeD0nMjI1JyBjeT0nNzUnIHI9JzInIGZpbGw9JyMwMDMwODcnIG9wYWNpdHk9JzAuMTInLz4KICA8cmVjdCB4PScxMDUnIHk9JzEwNScgd2lkdGg9JzkwJyBoZWlnaHQ9JzkwJyByeD0nNCcgZmlsbD0nbm9uZScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4xJy8+CiAgPGxpbmUgeDE9JzEzMCcgeTE9JzEwNScgeDI9JzEzMCcgeTI9JzkyJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGxpbmUgeDE9JzE1MCcgeTE9JzEwNScgeDI9JzE1MCcgeTI9JzkyJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGxpbmUgeDE9JzE3MCcgeTE9JzEwNScgeDI9JzE3MCcgeTI9JzkyJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGxpbmUgeDE9JzEzMCcgeTE9JzE5NScgeDI9JzEzMCcgeTI9JzIwOCcgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4wOScvPgogIDxsaW5lIHgxPScxNTAnIHkxPScxOTUnIHgyPScxNTAnIHkyPScyMDgnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScxLjUnIG9wYWNpdHk9JzAuMDknLz4KICA8bGluZSB4MT0nMTcwJyB5MT0nMTk1JyB4Mj0nMTcwJyB5Mj0nMjA4JyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGxpbmUgeDE9JzEwNScgeTE9JzEzMCcgeDI9JzkyJyB5Mj0nMTMwJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGxpbmUgeDE9JzEwNScgeTE9JzE1MCcgeDI9JzkyJyB5Mj0nMTUwJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGxpbmUgeDE9JzEwNScgeTE9JzE3MCcgeDI9JzkyJyB5Mj0nMTcwJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGxpbmUgeDE9JzE5NScgeTE9JzEzMCcgeDI9JzIwOCcgeTI9JzEzMCcgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4wOScvPgogIDxsaW5lIHgxPScxOTUnIHkxPScxNTAnIHgyPScyMDgnIHkyPScxNTAnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScxLjUnIG9wYWNpdHk9JzAuMDknLz4KICA8bGluZSB4MT0nMTk1JyB5MT0nMTcwJyB4Mj0nMjA4JyB5Mj0nMTcwJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjA5Jy8+CiAgPGNpcmNsZSBjeD0nMzAnIGN5PSczMCcgcj0nNCcgZmlsbD0nbm9uZScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4xJy8+CiAgPGNpcmNsZSBjeD0nMjcwJyBjeT0nMjcwJyByPSc0JyBmaWxsPSdub25lJyBzdHJva2U9JyMwMDMwODcnIHN0cm9rZS13aWR0aD0nMS41JyBvcGFjaXR5PScwLjEnLz4KICA8Y2lyY2xlIGN4PScyNzAnIGN5PSczMCcgcj0nNCcgZmlsbD0nbm9uZScgc3Ryb2tlPScjMDAzMDg3JyBzdHJva2Utd2lkdGg9JzEuNScgb3BhY2l0eT0nMC4xJy8+CiAgPGNpcmNsZSBjeD0nMzAnIGN5PScyNzAnIHI9JzQnIGZpbGw9J25vbmUnIHN0cm9rZT0nIzAwMzA4Nycgc3Ryb2tlLXdpZHRoPScxLjUnIG9wYWNpdHk9JzAuMScvPgo8L3N2Zz4K");
    background-size: 300px 300px;
    color: var(--gray-text);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    line-height: 1.6;
}

/* ─── Top accent bar ─────────────────────────────────────── */
body::before {
    content: '';
    display: block;
    height: 4px;
    background: linear-gradient(90deg, var(--usc-blue) 0%, var(--usc-gold) 50%, var(--usc-blue) 100%);
    position: sticky;
    top: 0;
    z-index: 100;
}

/* ─── Header ─────────────────────────────────────────────── */
header {
    background: var(--usc-blue);
    color: var(--white);
    padding: 0 32px;
    display: flex;
    align-items: center;
    gap: 20px;
    min-height: 80px;
    box-shadow: 0 3px 12px rgba(0,0,0,.30);
    position: sticky;
    top: 4px;
    z-index: 99;
}

.usc-seal {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 2.5px solid var(--usc-gold);
    overflow: hidden;
    padding: 3px;
}

.usc-seal-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

.dept-info h1 {
    font-size: 1rem;
    font-weight: 700;
    line-height: 1.25;
    color: var(--white);
}

.dept-info p {
    font-size: .78rem;
    color: var(--usc-gold);
    font-weight: 500;
    margin-top: 1px;
}

.dept-info .dept-sub {
    font-size: .72rem;
    color: rgba(255,255,255,.65);
    margin-top: 1px;
}

header nav {
    margin-left: auto;
    display: flex;
    gap: 8px;
    align-items: center;
}

header nav a {
    color: var(--white);
    text-decoration: none;
    font-size: .85rem;
    font-weight: 500;
    padding: 7px 16px;
    border: 1.5px solid rgba(255,255,255,.3);
    border-radius: 20px;
    transition: all .2s;
    white-space: nowrap;
}

header nav a:hover {
    background: var(--usc-gold);
    border-color: var(--usc-gold);
    color: var(--usc-blue-dark);
    font-weight: 600;
}

/* ─── Hero banner (landing page) ────────────────────────── */
.hero-banner {
    background: linear-gradient(135deg, var(--usc-blue-dark) 0%, var(--usc-blue) 60%, var(--usc-blue-mid) 100%);
    color: var(--white);
    padding: 40px 32px 36px;
    text-align: center;
    margin-bottom: 0;
    position: relative;
    overflow: hidden;
}

.hero-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.hero-banner h2 {
    font-size: 1.6rem;
    font-weight: 800;
    margin-bottom: 6px;
    position: relative;
}

.hero-banner p {
    font-size: .92rem;
    opacity: .85;
    position: relative;
}

.hero-gold-bar {
    height: 3px;
    width: 60px;
    background: var(--usc-gold);
    margin: 12px auto 0;
    border-radius: 2px;
}

/* ─── Main content area ──────────────────────────────────── */
main {
    flex: 1;
    padding: 32px 16px 40px;
    max-width: 1000px;
    width: 100%;
    margin: 0 auto;
}

/* ─── Cards ──────────────────────────────────────────────── */
.card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 32px;
    margin-bottom: 24px;
    border: 1px solid rgba(0,48,135,.06);
}

.card-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--usc-blue);
    margin-bottom: 22px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--usc-gold);
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ─── Choice buttons (landing page) ─────────────────────── */
.choice-grid {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
    padding: 32px 16px;
    background: var(--gray-bg);
}

.choice-btn {
    flex: 1 1 220px;
    max-width: 300px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 14px;
    padding: 36px 24px;
    background: var(--white);
    color: var(--usc-blue);
    border-radius: var(--radius);
    text-decoration: none;
    font-size: 1rem;
    font-weight: 700;
    transition: all .25s;
    text-align: center;
    box-shadow: var(--shadow);
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
}

.choice-btn::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--usc-gold);
    transform: scaleX(0);
    transition: transform .25s;
}

.choice-btn:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--usc-blue);
}

.choice-btn:hover::before {
    transform: scaleX(1);
}

.choice-btn .icon {
    font-size: 2.6rem;
    line-height: 1;
}

.choice-btn .btn-label {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--usc-blue);
}

.choice-btn .btn-sub {
    font-size: .78rem;
    font-weight: 400;
    color: var(--gray-muted);
}

.choice-btn.primary-action {
    background: var(--usc-blue);
    color: var(--white);
    border-color: var(--usc-blue);
}

.choice-btn.primary-action .btn-label,
.choice-btn.primary-action .btn-sub { color: var(--white); }
.choice-btn.primary-action .btn-sub { opacity: .8; }
.choice-btn.primary-action:hover { background: var(--usc-blue-mid); border-color: var(--usc-blue-mid); }
.choice-btn.primary-action::before { background: var(--usc-gold); }

.choice-btn.danger-action {
    background: var(--red);
    color: var(--white);
    border-color: var(--red);
}

.choice-btn.danger-action .btn-label,
.choice-btn.danger-action .btn-sub { color: var(--white); }
.choice-btn.danger-action .btn-sub { opacity: .8; }
.choice-btn.danger-action:hover { background: #a93226; border-color: #a93226; }
.choice-btn.danger-action::before { background: #ffc0b8; }

/* ─── Forms ──────────────────────────────────────────────── */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.form-grid .span-2 {
    grid-column: span 2;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

label {
    font-size: .83rem;
    font-weight: 700;
    color: var(--usc-blue);
    text-transform: uppercase;
    letter-spacing: .4px;
}

label .req { color: var(--red); }

input[type="text"],
input[type="email"],
input[type="tel"],
input[type="date"],
input[type="password"],
select,
textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-border);
    border-radius: var(--radius-sm);
    font-size: .95rem;
    color: var(--gray-text);
    background: var(--white);
    transition: border-color .2s, box-shadow .2s;
    font-family: inherit;
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--usc-blue);
    box-shadow: 0 0 0 3px rgba(0,48,135,.12);
}

.field-note {
    font-size: .76rem;
    color: var(--gray-muted);
    font-style: italic;
}

.section-label {
    font-size: .8rem;
    font-weight: 800;
    color: var(--usc-gold-dark);
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 6px 10px;
    background: var(--usc-gold-pale);
    border-left: 3px solid var(--usc-gold);
    border-radius: 0 4px 4px 0;
    margin: 4px 0;
}

/* ─── Buttons ─────────────────────────────────────────────── */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 24px;
    border: 2px solid transparent;
    border-radius: 20px;
    font-size: .9rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s;
    text-align: center;
    font-family: inherit;
    letter-spacing: .2px;
}

.btn-primary {
    background: var(--usc-blue);
    color: var(--white);
    border-color: var(--usc-blue);
}

.btn-primary:hover {
    background: var(--usc-blue-mid);
    border-color: var(--usc-blue-mid);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,48,135,.25);
}

.btn-gold {
    background: var(--usc-gold);
    color: var(--usc-blue-dark);
    border-color: var(--usc-gold);
}

.btn-gold:hover {
    background: var(--usc-gold-dark);
    border-color: var(--usc-gold-dark);
    transform: translateY(-1px);
}

.btn-danger {
    background: var(--red);
    color: var(--white);
    border-color: var(--red);
}

.btn-danger:hover {
    background: #a93226;
    border-color: #a93226;
}

.btn-outline {
    background: transparent;
    color: var(--usc-blue);
    border-color: var(--usc-blue);
}

.btn-outline:hover {
    background: var(--usc-blue-light);
}

.btn-sm {
    padding: 5px 14px;
    font-size: .8rem;
    border-radius: 14px;
}

.btn-block {
    width: 100%;
    display: flex;
}

/* ─── Alerts ─────────────────────────────────────────────── */
.alert {
    padding: 13px 18px;
    border-radius: var(--radius-sm);
    margin-bottom: 20px;
    font-size: .92rem;
    font-weight: 500;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.alert-success {
    background: #eaf7ee;
    color: #145a2b;
    border-left: 4px solid var(--green);
}

.alert-error {
    background: #fdf0ef;
    color: #7b1a14;
    border-left: 4px solid var(--red);
}

.alert-info {
    background: var(--usc-blue-light);
    color: var(--usc-blue-dark);
    border-left: 4px solid var(--usc-blue);
}

/* ─── Tables ─────────────────────────────────────────────── */
.table-wrapper {
    overflow-x: auto;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-border);
}

table {
    width: 100%;
    border-collapse: collapse;
    background: var(--white);
    font-size: .86rem;
}

thead {
    background: linear-gradient(135deg, var(--usc-blue-dark) 0%, var(--usc-blue) 100%);
    color: var(--white);
}

thead th {
    padding: 13px 14px;
    text-align: left;
    font-weight: 700;
    white-space: nowrap;
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .5px;
}

thead th:first-child { border-radius: var(--radius) 0 0 0; }
thead th:last-child  { border-radius: 0 var(--radius) 0 0; }

tbody tr {
    border-bottom: 1px solid var(--gray-border);
    transition: background .15s;
}

tbody tr:hover { background: var(--usc-gold-pale); }
tbody tr:last-child { border-bottom: none; }

tbody td { padding: 11px 14px; vertical-align: middle; }

.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.badge-in  { background: #d4f0de; color: #145a2b; }
.badge-out { background: #e9eaec; color: #4a4f5a; }

/* ─── Stats bar (dashboard) ──────────────────────────────── */
.stats-bar {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.stat-card {
    flex: 1 1 160px;
    background: var(--white);
    border-radius: var(--radius);
    padding: 20px 24px;
    box-shadow: var(--shadow);
    border-top: 4px solid var(--usc-blue);
    transition: transform .2s;
}

.stat-card:hover { transform: translateY(-2px); }
.stat-card.gold-accent { border-top-color: var(--usc-gold); }
.stat-card.green-accent { border-top-color: var(--green); }

.stat-value {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--usc-blue);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: .78rem;
    color: var(--gray-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
}

/* ─── Search filters ─────────────────────────────────────── */
.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.filter-item label {
    font-size: .75rem;
}

/* ─── Info preview panel ─────────────────────────────────── */
.info-preview {
    background: linear-gradient(135deg, var(--usc-blue-light) 0%, var(--usc-gold-pale) 100%);
    border-radius: var(--radius);
    padding: 22px;
    margin: 16px 0;
    border: 1px solid rgba(0,48,135,.12);
}

.info-preview h3 {
    color: var(--usc-blue);
    margin-bottom: 14px;
    font-size: .95rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px 24px;
    font-size: .9rem;
}

.info-grid dt {
    font-weight: 700;
    color: var(--usc-blue);
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.info-grid dd {
    color: var(--gray-text);
}

/* ─── Modal ──────────────────────────────────────────────── */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,31,90,.6);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 16px;
    backdrop-filter: blur(2px);
}

.modal-box {
    background: var(--white);
    border-radius: var(--radius);
    max-width: 700px;
    width: 100%;
    max-height: 82vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 12px 50px rgba(0,0,0,.35);
}

.modal-header {
    background: linear-gradient(135deg, var(--usc-blue-dark), var(--usc-blue));
    color: var(--white);
    padding: 16px 22px;
    border-radius: var(--radius) var(--radius) 0 0;
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 3px solid var(--usc-gold);
}

.modal-header span {
    font-weight: 700;
    font-size: 1rem;
}

.modal-close {
    margin-left: auto;
    background: none;
    border: none;
    color: var(--white);
    font-size: 1.5rem;
    cursor: pointer;
    line-height: 1;
    opacity: .8;
    transition: opacity .2s;
    padding: 0 4px;
}

.modal-close:hover { opacity: 1; }

.modal-body { overflow-y: auto; flex: 1; }

.modal-footer {
    padding: 14px 22px;
    text-align: right;
    border-top: 1px solid var(--gray-border);
    background: var(--gray-bg);
    border-radius: 0 0 var(--radius) var(--radius);
}

/* ─── Login card ─────────────────────────────────────────── */
.login-wrapper {
    max-width: 440px;
    margin: 0 auto;
}

.login-card-header {
    background: linear-gradient(135deg, var(--usc-blue-dark), var(--usc-blue));
    border-radius: var(--radius) var(--radius) 0 0;
    padding: 28px 32px 24px;
    text-align: center;
    color: var(--white);
    margin: -32px -32px 28px;
}

.login-card-header .seal-large {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: var(--white);
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid var(--usc-gold);
}

.login-card-header h2 {
    font-size: 1.2rem;
    font-weight: 800;
    margin-bottom: 2px;
}

.login-card-header p {
    font-size: .8rem;
    opacity: .8;
    color: var(--usc-gold);
}

/* ─── Footer ─────────────────────────────────────────────── */
footer {
    background: var(--usc-blue-dark);
    color: rgba(255,255,255,.65);
    text-align: center;
    padding: 20px 16px;
    font-size: .8rem;
    margin-top: auto;
    border-top: 3px solid var(--usc-gold);
}

footer strong { color: var(--usc-gold); }
footer a { color: var(--usc-gold); }

footer .footer-motto {
    font-style: italic;
    color: rgba(255,255,255,.4);
    font-size: .72rem;
    margin-top: 4px;
    letter-spacing: .5px;
}

/* ─── Divider ─────────────────────────────────────────────── */
.divider {
    border: none;
    border-top: 1px solid var(--gray-border);
    margin: 22px 0;
}

.gold-divider {
    border: none;
    border-top: 2px solid var(--usc-gold);
    margin: 22px 0;
    opacity: .5;
}

/* ─── Utilities ──────────────────────────────────────────── */
.text-center { text-align: center; }
.mt-8  { margin-top: 8px; }
.mt-16 { margin-top: 16px; }
.mt-24 { margin-top: 24px; }
.mb-8  { margin-bottom: 8px; }
.flex  { display: flex; }
.gap-8 { gap: 8px; }
.flex-wrap { flex-wrap: wrap; }
.align-center { align-items: center; }

/* ─── Responsive ─────────────────────────────────────────── */
@media (max-width: 640px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-grid .span-2 { grid-column: span 1; }
    .info-grid { grid-template-columns: 1fr; }
    .filters-grid { grid-template-columns: 1fr; }
    header { flex-wrap: wrap; padding: 12px 16px; min-height: auto; gap: 12px; }
    header nav { margin-left: 0; width: 100%; }
    .choice-grid { flex-direction: column; align-items: stretch; padding: 20px 16px; }
    .choice-btn { max-width: 100%; }
    .stats-bar { flex-direction: column; }
    .hero-banner { padding: 28px 16px; }
    .hero-banner h2 { font-size: 1.3rem; }
    main { padding: 20px 12px 32px; }
}
