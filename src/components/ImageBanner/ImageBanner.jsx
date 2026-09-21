import Copy from "../Copy/Copy";

import "./ImageBanner.css";

const ImageBanner = ({
  image = "/image-banner/image-banner.jpg",
  heading = ["More", "Stay"],
  logo = null,
  description = "Salle Blanche, every detail is designed to make you feel at home with the elegance of Florence just beyond your door.",
}) => {
  return (
    <section className="image-banner">
      <div className="image-banner-bg">
        <img src={image} alt="" />
      </div>

      <div className="container">
        <div className="image-banner-heading">
          <Copy type="lines" animateOnScroll>
            <h4>{heading[0]}</h4>
          </Copy>
          {logo && <img className="image-banner-logo" src={logo} alt="" />}
          <Copy type="lines" animateOnScroll>
            <h4>{heading[1]}</h4>
          </Copy>
        </div>

        <div className="image-banner-footer">
          <Copy
            type="lines"
            trigger=".image-banner"
            start="top 50%"
            delay={0.5}
          >
            <p>{description}</p>
          </Copy>
        </div>
      </div>
    </section>
  );
};

export default ImageBanner;
