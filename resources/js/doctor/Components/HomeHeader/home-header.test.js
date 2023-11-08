/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import HomeHeaderComponent from "./HomeHeader.vue";

describe("HomeHeaderComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(HomeHeaderComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
