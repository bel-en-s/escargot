import { TOPOGRAPHIC_SVG, LOGO_SVG } from "./svgs";

import "./TopographicMap.css";

export function TopographicMap() {
  return (
    <div
      className="topographic-map"
      aria-hidden="true"
      dangerouslySetInnerHTML={{ __html: TOPOGRAPHIC_SVG }}
    />
  );
}

export function LogoMark() {
  return (
    <div
      className="logo-mark"
      aria-hidden="true"
      dangerouslySetInnerHTML={{ __html: LOGO_SVG }}
    />
  );
}
