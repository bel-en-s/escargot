"use client";

import { useEffect, useState } from "react";
import { useLenis } from "lenis/react";

import {
  TopographicMap,
  LogoMark,
} from "@/components/TopographicMap/TopographicMap";

import "./Preloader.css";

const REVEAL_MS = 3200;
const EXIT_MS = 3600;
const EXIT_DURATION_MS = 1000;

let isInitialLoad = true;

export default function Preloader() {
  const lenis = useLenis();
  const [isVisible, setIsVisible] = useState(isInitialLoad);
  const [isExiting, setIsExiting] = useState(false);

  /* lock scroll while the preloader is on screen */
  useEffect(() => {
    if (!isVisible) return;

    if (lenis) lenis.stop();
    document.body.style.overflow = "hidden";

    return () => {
      if (lenis) lenis.start();
      document.body.style.overflow = "";
    };
  }, [lenis, isVisible]);

  /* reveal the page content, then fade the preloader out */
  useEffect(() => {
    if (!isVisible) return;

    const revealTimer = setTimeout(() => {
      window.__preloaderRevealed = true;
      window.dispatchEvent(new Event("preloader:reveal"));
    }, REVEAL_MS);

    const exitTimer = setTimeout(() => setIsExiting(true), EXIT_MS);

    const unmountTimer = setTimeout(() => {
      isInitialLoad = false;
      setIsVisible(false);
    }, EXIT_MS + EXIT_DURATION_MS);

    return () => {
      clearTimeout(revealTimer);
      clearTimeout(exitTimer);
      clearTimeout(unmountTimer);
    };
  }, [isVisible]);

  if (!isVisible) return null;

  return (
    <section
      className={`preloader ${isExiting ? "is-exiting" : ""}`}
      aria-label="Website preloader"
    >
      <TopographicMap />
      <LogoMark />
    </section>
  );
}
