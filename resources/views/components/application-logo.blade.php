<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <circle cx="100" cy="100" r="95" fill="none" stroke="#d97706" stroke-width="3"/>
    <circle cx="100" cy="100" r="88" fill="none" stroke="#d97706" stroke-width="1" stroke-dasharray="4"/>

    <circle cx="100" cy="100" r="80" fill="#fffbeb"/>

    <g transform="translate(100, 105)">
        <circle r="55" fill="#d97706" opacity="0.1"/>
        <path d="M-40,-10 C-50,30 40,50 45,-5 C50,-50 -30,-50 -35,-15 C-40,15 30,25 30,-5 C30,-25 -15,-25 -15,-10" 
              fill="none" stroke="#b45309" stroke-width="12" stroke-linecap="round"/>
        <ellipse cx="-55" cy="-25" rx="5" ry="8" fill="#d97706" transform="rotate(-30, -55, -25)"/>
        <ellipse cx="60" cy="35" rx="5" ry="8" fill="#d97706" transform="rotate(45, 60, 35)"/>
    </g>

    <defs>
        <path id="anoraPath" d="M 40, 100 A 60, 60 0 0, 1 160, 100" />
    </defs>
    <text font-family="Serif" font-size="19" font-weight="900" letter-spacing="2" fill="#78350f">
        <textPath href="#anoraPath" startOffset="50%" text-anchor="middle">
            ANORA CAFÉ
        </textPath>
    </text>
</svg>