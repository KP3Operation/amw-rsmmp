/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import FeePaidCardComponent from "./FeePaidCard.vue";

describe("FeePaidCardComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(FeePaidCardComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
