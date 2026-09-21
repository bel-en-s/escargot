"use client";

import { useEffect, useRef } from "react";
import gsap from "gsap";

import "./Header.css";

const HEADER_LINKS = [
  { label: "About", href: "#about" },
  { label: "Destination", href: "#destination" },
  { label: "Contact", href: "#contact", button: true },
];

export default function Header() {
  const wordmarkRef = useRef(null);
  const isShownRef = useRef(false);

  useEffect(() => {
    const wordmark = wordmarkRef.current;
    if (!wordmark) return;

    gsap.set(wordmark, { width: 0, opacity: 0 });

    const onScroll = () => {
      const shouldShow = window.scrollY > window.innerHeight * 0.8;
      if (shouldShow === isShownRef.current) return;
      isShownRef.current = shouldShow;

      if (shouldShow) {
        gsap.to(wordmark, {
          width: wordmark.scrollWidth,
          opacity: 1,
          duration: 0.9,
          ease: "power4.out",
        });
      } else {
        gsap.to(wordmark, {
          width: 0,
          opacity: 0,
          duration: 0.4,
          ease: "power3.in",
        });
      }
    };

    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  return (
    <header className="site-header">
      <div className="site-header-line" />
      <div className="site-header-inner">
        <div className="site-header-brand">
          <a className="site-logo" href="#">
            <img src="/logo.png" alt="Escargot" />
          </a>
          <span className="site-wordmark" ref={wordmarkRef}>
            Escargot <em>Adventures</em>
          </span>
        </div>
        <nav className="site-nav">
          {HEADER_LINKS.map((link) => (
            <a
              key={link.label}
              href={link.href}
              className={link.button ? "site-nav-button" : undefined}
            >
              {link.label}
            </a>
          ))}
        </nav>
      </div>
      <div className="site-header-line" />
    </header>
  );
}
