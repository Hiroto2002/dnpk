import type {
  ComponentPropsWithoutRef,
  CSSProperties,
  ElementType,
  ReactNode,
} from "react";

export type FlexProps<E extends ElementType = "div"> = {
  as?: E;
  children?: ReactNode;
  direction?: CSSProperties["flexDirection"];
  align?: CSSProperties["alignItems"];
  justify?: CSSProperties["justifyContent"];
  wrap?: CSSProperties["flexWrap"];
  gap?: CSSProperties["gap"];
  inline?: boolean;
  style?: CSSProperties;
} & Omit<ComponentPropsWithoutRef<E>, "children" | "style">;

export function Flex<E extends ElementType = "div">({
  as,
  children,
  direction = "row",
  align,
  justify,
  wrap,
  gap,
  inline = false,
  style,
  ...rest
}: FlexProps<E>) {
  const Component = (as ?? "div") as ElementType;
  const flexStyle: CSSProperties = {
    display: inline ? "inline-flex" : "flex",
    flexDirection: direction,
    alignItems: align,
    justifyContent: justify,
    flexWrap: wrap,
    gap,
    ...style,
  };

  return (
    <Component style={flexStyle} {...(rest as ComponentPropsWithoutRef<ElementType>)}>
      {children}
    </Component>
  );
}
