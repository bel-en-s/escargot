"use client";

import React, { useRef } from "react";
import gsap from "gsap";
import { SplitText } from "gsap/SplitText";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { useGSAP } from "@gsap/react";

import "./Copy.css";

gsap.registerPlugin(SplitText, ScrollTrigger);

const REQUIRED_FONTS = ["Host Grotesk", "DM Mono", "Playfair Display"];

async function waitForFonts() {
  try {
    await document.fonts.ready;
    REQUIRED_FONTS.forEach((font) => document.fonts.check(`16px "${font}"`));
    await new Promise((resolve) => setTimeout(resolve, 100));
  } catch {
    await new Promise((resolve) => setTimeout(resolve, 200));
  }
}

function resolveTriggerElement(selector, fallback) {
  if (typeof selector === "string" && selector.trim().length > 0) {
    return (
      fallback.closest(selector) || document.querySelector(selector) || fallback
    );
  }
  return fallback;
}

export default function Copy({
  children,
  animateOnScroll = true,
  delay = 0,
  type = "lines",
  trigger = null,
  triggerPoint = null,
  start = null,
  mode = "slide",
  blockColor = "#3e4c05",
  waitForPreloader = false,
  stagger = 0.15,
  duration = 0.75,
}) {
  const containerRef = useRef(null);
  const splitInstanceRefs = useRef([]);
  const scrollTriggerRefs = useRef([]);
  const revealListenerRef = useRef(null);

  useGSAP(
    () => {
      if (!containerRef.current) return;

      let isActive = true;

      const cleanupInstances = () => {
        scrollTriggerRefs.current.forEach((st) => st?.kill());
        scrollTriggerRefs.current = [];

        splitInstanceRefs.current.forEach((split) => split?.revert());
        splitInstanceRefs.current = [];

        const wrappers = containerRef.current?.querySelectorAll(
          ".block-line-wrapper",
        );
        wrappers?.forEach((wrapper) => {
          if (wrapper.parentNode && wrapper.firstChild) {
            wrapper.parentNode.insertBefore(wrapper.firstChild, wrapper);
            wrapper.remove();
          }
        });

        if (revealListenerRef.current) {
          window.removeEventListener(
            "preloader:reveal",
            revealListenerRef.current,
          );
          revealListenerRef.current = null;
        }
      };

      const buildAnimations = async () => {
        await waitForFonts();
        if (!isActive || !containerRef.current) return;

        cleanupInstances();

        const targetElements = containerRef.current.hasAttribute(
          "data-copy-wrapper",
        )
          ? Array.from(containerRef.current.children)
          : [containerRef.current];

        const resolvedStart = start ?? "top 80%";

        if (mode === "block") {
          buildBlockReveal(targetElements, resolvedStart);
          return;
        }

        buildSlideReveal(targetElements, resolvedStart);
      };

      const buildBlockReveal = (targetElements, resolvedStart) => {
        const lineRefs = [];
        const blockRefs = [];

        targetElements.forEach((element) => {
          const split = SplitText.create(element, {
            type: "lines",
            linesClass: "block-line++",
            lineThreshold: 0.1,
          });
          splitInstanceRefs.current.push(split);

          split.lines.forEach((line) => {
            const wrapper = document.createElement("div");
            wrapper.className = "block-line-wrapper";
            line.parentNode.insertBefore(wrapper, line);
            wrapper.appendChild(line);

            const block = document.createElement("div");
            block.className = "block-revealer";
            block.style.backgroundColor = blockColor;
            wrapper.appendChild(block);

            lineRefs.push(line);
            blockRefs.push(block);
          });
        });

        gsap.set(lineRefs, { opacity: 0 });
        gsap.set(blockRefs, { scaleX: 0, transformOrigin: "left center" });

        const reveal = () => {
          blockRefs.forEach((block, index) => {
            const line = lineRefs[index];
            const tl = gsap.timeline({ delay: delay + index * stagger });

            tl.to(block, {
              scaleX: 1,
              duration,
              ease: "power4.inOut",
            });
            tl.set(line, { opacity: 1 });
            tl.set(block, { transformOrigin: "right center" });
            tl.to(block, {
              scaleX: 0,
              duration,
              ease: "power4.inOut",
            });
          });
        };

        if (animateOnScroll) {
          const triggerElement = resolveTriggerElement(
            triggerPoint ?? trigger,
            containerRef.current,
          );

          const scrollTrigger = ScrollTrigger.create({
            trigger: triggerElement,
            start: resolvedStart,
            once: true,
            onEnter: reveal,
            refreshPriority: -1,
          });
          scrollTriggerRefs.current.push(scrollTrigger);
          return;
        }

        if (waitForPreloader) {
          if (window.__preloaderRevealed) {
            reveal();
            return;
          }

          const handler = () => reveal();
          revealListenerRef.current = handler;
          window.addEventListener("preloader:reveal", handler, { once: true });
          return;
        }

        reveal();
      };

      const buildSlideReveal = (targetElements, resolvedStart) => {
        const resolvedType = type === "words" ? "words" : "lines";
        const splitUnits = [];

        targetElements.forEach((element) => {
          const isWordSplit = resolvedType === "words";

          const split = SplitText.create(element, {
            type: isWordSplit ? "words" : "lines",
            mask: isWordSplit ? "words" : "lines",
            ...(isWordSplit
              ? { wordsClass: "word" }
              : { linesClass: "line", lineThreshold: 0.1 }),
          });

          splitInstanceRefs.current.push(split);

          const units = isWordSplit ? split.words : split.lines;

          /* preserve text-indent on first split unit */
          const computedStyle = window.getComputedStyle(element);
          const textIndent = computedStyle.textIndent;
          if (textIndent && textIndent !== "0px" && units.length > 0) {
            units[0].style.paddingLeft = textIndent;
            element.style.textIndent = "0";
          }

          splitUnits.push(...units);
        });

        gsap.set(splitUnits, { y: "110%" });

        const revealAnimation = gsap.to(splitUnits, {
          y: "0%",
          duration: 1,
          ease: "power4.out",
          stagger: 0.1,
          delay,
          paused: animateOnScroll,
        });

        if (animateOnScroll) {
          const triggerElement = resolveTriggerElement(
            triggerPoint ?? trigger,
            containerRef.current,
          );

          const scrollTrigger = ScrollTrigger.create({
            trigger: triggerElement,
            start: resolvedStart,
            animation: revealAnimation,
            once: true,
            refreshPriority: -1,
          });
          scrollTriggerRefs.current.push(scrollTrigger);
        }
      };

      buildAnimations();

      return () => {
        isActive = false;
        cleanupInstances();
      };
    },
    {
      scope: containerRef,
      dependencies: [
        animateOnScroll,
        delay,
        type,
        trigger,
        triggerPoint,
        start,
        mode,
        blockColor,
        waitForPreloader,
        stagger,
        duration,
      ],
    },
  );

  if (React.Children.count(children) === 1 && React.isValidElement(children)) {
    return React.cloneElement(children, { ref: containerRef });
  }

  return (
    <div ref={containerRef} data-copy-wrapper="true">
      {children}
    </div>
  );
}
